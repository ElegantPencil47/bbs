from flask import Flask, request, redirect, render_template, url_for, g, make_response, abort
from markupsafe import Markup, escape
import sqlite3
from datetime import datetime, timedelta
from flask_socketio import SocketIO, join_room, leave_room

# -------------------------------
# Flask アプリ初期化
# -------------------------------
app = Flask(__name__)
app.secret_key = 'your_super_secret_key_here'
socketio = SocketIO(app)

DATABASE = "bbs.db"
last_posts = {}
online_users = {}  # {thread_id: set of sid}

# -------------------------------
# データベース接続
# -------------------------------
def get_db():
    db = getattr(g, "_database", None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
        db.row_factory = sqlite3.Row
    return db

@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, "_database", None)
    if db is not None:
        db.close()

# -------------------------------
# DB初期化
# -------------------------------
def init_db():
    with app.app_context():
        db = get_db()
        c = db.cursor()
        c.execute("""
            CREATE TABLE IF NOT EXISTS threads (
                id INTEGER PRIMARY KEY,
                title TEXT,
                created_at TEXT
            )
        """)
        c.execute("""
            CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY,
                thread_id INTEGER,
                name TEXT,
                message TEXT,
                created_at TEXT
            )
        """)
        db.commit()

# -------------------------------
# テーマ変更
# -------------------------------
@app.route("/set_theme", methods=["POST"])
def set_theme():
    theme = request.form["theme"]
    resp = make_response(redirect("/"))
    resp.set_cookie("theme", theme)
    return resp

# -------------------------------
# トップページ（スレッド一覧）
# -------------------------------
@app.route("/")
def index():
    theme = request.cookies.get("theme", "d")
    db = get_db()
    c = db.cursor()
    c.execute("""
        SELECT t.id, t.title, t.created_at, COUNT(p.id) as count
        FROM threads t
        LEFT JOIN posts p ON t.id = p.thread_id
        GROUP BY t.id
        ORDER BY t.created_at DESC
    """)
    threads = c.fetchall()
    return render_template("index.html", threads=threads, theme=theme)

# -------------------------------
# 新規スレッド作成
# -------------------------------
@app.route("/thread/new", methods=["POST"])
def new_thread():
    title = request.form["title"]
    name = request.form["name"] or "名も無き者"
    message = request.form["message"]
    db = get_db()
    c = db.cursor()

    try:
        c.execute(
            "INSERT INTO threads (title, created_at) VALUES (?, ?)",
            (title, datetime.now().isoformat())
        )
        new_thread_id = c.lastrowid
        c.execute(
            "INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
            (new_thread_id, name, message, datetime.now().isoformat())
        )
        db.commit()
    except sqlite3.Error as e:
        db.rollback()
        print(f"Database error: {e}")
        return redirect(url_for("index"))

    return redirect(url_for("thread", thread_id=new_thread_id))

# -------------------------------
# 改行を <br> に変換するフィルタ
# -------------------------------
@app.template_filter("nl2br")
def nl2br(s):
    if s is None:
        return ""
    return Markup("<br>").join(escape(s).splitlines())

# -------------------------------
# 投稿制限（レート制限40秒）
# -------------------------------
@app.before_request
def rate_limit():
    if request.endpoint in ("new_thread", "thread") and request.method == "POST":
    ip = request.remote_addr
    now = datetime.now()
    if ip in last_posts and now - last_posts[ip] < timedelta(seconds=40):
        abort(429)
    last_posts[ip] = now

# -------------------------------
# デフォルトテーマ設定
# -------------------------------
@app.before_request
def ensure_theme_cookie():
    theme = request.cookies.get("theme")
    if theme is None:
        g.set_default_theme = True
    else:
        g.set_default_theme = False

@app.after_request
def apply_theme_cookie(response):
    if getattr(g, "set_default_theme", False):
        response.set_cookie("theme", "d", max_age=60*60*24*30)
    return response

# -------------------------------
# Socket.IO：同時接続人数カウント
# -------------------------------
@socketio.on("join_thread")
def on_join(data):
    thread_id = data["thread_id"]
    if thread_id not in online_users:
        online_users[thread_id] = set()
    online_users[thread_id].add(request.sid)
    join_room(thread_id)
    socketio.emit("online_count", len(online_users[thread_id]), room=thread_id)

@socketio.on("leave_thread")
def on_leave(data):
    thread_id = data["thread_id"]
    if thread_id in online_users and request.sid in online_users[thread_id]:
        online_users[thread_id].remove(request.sid)
        leave_room(thread_id)
        socketio.emit("online_count", len(online_users[thread_id]), room=thread_id)

# -------------------------------
# スレッドページ
# -------------------------------
@app.route("/thread/<int:thread_id>", methods=["GET", "POST"])
def thread(thread_id):
    db = get_db()
    c = db.cursor()

    if request.method == "POST":
        name = request.form["name"] or "名も無き者"
        message = request.form["message"]
        try:
            c.execute(
                "INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
                (thread_id, name, message, datetime.now().isoformat())
            )
            db.commit()
        except sqlite3.Error as e:
            db.rollback()
            print(f"Database error: {e}")
        finally:
            return redirect(url_for("thread", thread_id=thread_id))

    c.execute("SELECT id, title FROM threads WHERE id=?", (thread_id,))
    thread_data = c.fetchone()
    c.execute("SELECT name, message, created_at FROM posts WHERE thread_id=? ORDER BY id ASC", (thread_id,))
    posts = c.fetchall()
    posts_with_numbers = [(i + 1, p['name'], p['message'], p['created_at']) for i, p in enumerate(posts)]

    return render_template("thread.html", thread=thread_data, posts=posts_with_numbers)

# -------------------------------
# アプリ起動
# -------------------------------
if __name__ == "__main__":
    init_db()
    socketio.run(app, debug=True)