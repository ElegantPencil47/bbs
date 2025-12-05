from flask import Flask, request, redirect, render_template, url_for, make_response, abort, jsonify
from flask_cors import CORS
from flask_socketio import SocketIO, join_room
import os
from datetime import datetime
from werkzeug.utils import secure_filename
from flask import g
import sqlite3
from datetime import timedelta
from flask_socketio import SocketIO, join_room, leave_room



# -------------------------------
# Flask アプリ初期化
# -------------------------------
app = Flask(__name__)
app.secret_key = 'your_super_secret_key_here'
socketio = SocketIO(app, cors_allowed_origins="*", async_mode="eventlet")
CORS(app, resources={r"/*": {"origins": "*"}}) 





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


# 他の関数はそのまま

# Flask アプリケーションコード全体...

@app.route("/")
def index():
    theme = request.cookies.get("theme", "d")
    db = get_db()
    c = db.cursor()
    
    # すべてのスレッドと書き込み数を取得
    c.execute("""
        SELECT t.id, t.title, t.created_at, COUNT(p.id) as count
        FROM threads t
        LEFT JOIN posts p ON t.id = p.thread_id
        GROUP BY t.id
        ORDER BY t.created_at DESC
    """)
    threads = c.fetchall()
    
    # 最も書き込みの多いスレッドを特定する
    most_popular_thread = None
    if threads:
        # スレッドのリストを書き込み数（'count'列）でソートし、最初の要素を取得する
        # sqlite3.Rowオブジェクトは辞書のようにキーでアクセスできる
        most_popular_thread = sorted(threads, key=lambda t: t['count'], reverse=True)[0]
    
    # テンプレートに両方のデータを渡す
    return render_template("index.html", threads=threads, theme=theme, most_popular_thread=most_popular_thread)




# -------------------------------
# 新規スレッド作成
# -------------------------------
@app.route("/thread/new", methods=["POST"])
def new_thread():
    title = request.form["title"]
    # 修正：nameが空欄の場合に "書き人知らず" を設定
    name = request.form.get("name")
    if not name:
        name = "書き人知らず"
    # 入力された名前に<br>を追加
    

    message = request.form["message"]
    message = "\n" + message

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
#外部リンク
# -------------------------------

@app.route('/aa')
def aa():
    return render_template('aa.html')

@app.route('/news')
def news():
    return render_template('news.html')

@app.route('/uekirikarikiri')
def uekirikarikiri():
    return render_template('uekirikarikiri.html')


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
        # 修正：nameが空欄の場合に "書き人知らず" を設定
        name = request.form.get("name")
        if not name:
            name = "書き人知らず"
        # 入力された名前に<br>を追加
        

        message = request.form.get("message")
        message = "\n" + message

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

# スレッド情報
    c.execute("SELECT id, title FROM threads WHERE id=?", (thread_id,))
    thread_data = c.fetchone()

# 投稿一覧を取得してレス番号（num）を付ける
    c.execute("SELECT id, name, message, created_at FROM posts WHERE thread_id=? ORDER BY id ASC", (thread_id,))
    rows = c.fetchall()
    posts_with_numbers = []
    for i, p in enumerate(rows):
        posts_with_numbers.append({
            "num": i + 1,
            "id": p["id"],
            "name": p["name"],
            "message": p["message"],
            "created_at": p["created_at"]
        })

    theme = request.cookies.get("theme", "d")
    return render_template(
        "thread.html",
        thread=thread_data,
        theme=theme,
        posts=posts_with_numbers,
        thread_id=thread_id
    )



@app.before_request
def ensure_theme_cookie():
    theme = request.cookies.get("theme")
    user_agent = request.headers.get("User-Agent", "").lower()

    # スマホっぽいUAなら theme-s をデフォルトに
    if theme is None:
        if "iphone" in user_agent or "android" in user_agent:
            g.set_default_theme = "theme-s"
        else:
            g.set_default_theme = "d"

@app.after_request
def apply_theme_cookie(response):
    if getattr(g, "set_default_theme", None):
        response.set_cookie("theme", g.set_default_theme, max_age=60*60*24*30)
    return response


# -------------------------------
# 書き込み一覧
# -------------------------------


@app.route("/thread/<int:thread_id>/add_post", methods=["POST"])
def add_post(thread_id):
    name = request.form.get("name") or "書き人知らず"
    
    message = request.form.get("message", "").strip()
    message = "\n" + message

    if not message:
        return jsonify({"error": "empty"}), 400

    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    db = get_db()
    c = db.cursor()
    c.execute(
        "INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
        (thread_id, name, message, now)
    )
    db.commit()

    # 投稿のIDを取得
    post_id = c.lastrowid

    # レス番号を計算
    c.execute("SELECT COUNT(*) FROM posts WHERE thread_id=?", (thread_id,))
    count = c.fetchone()[0]

    post_data = {
        "num": count,
        "id": post_id,
        "name": name,
        "created_at": now,
        "message": message
        
    }

    # Socket.IOでブロードキャスト
    socketio.emit("new_post", post_data, room=thread_id)

    return jsonify(post_data)



@app.route("/thread/<int:thread_id>/posts_json")
def posts_json(thread_id):
    db = get_db()
    cur = db.execute( "SELECT id, name, message, created_at FROM posts WHERE thread_id=? ORDER BY id", (thread_id,) )
    posts = cur.fetchall()
    return jsonify([ { "num": i+1,
    "name": (p["name"] if p["name"] else "書き人知らず"),
    "message": p["message"],
    "created_at": p["created_at"] }
    for i, p in enumerate(posts) ]) 






#===============================================================================================--

# ---- ファイルアップロード ----
@app.route("/upload", methods=["POST"])
def upload():
    file = request.files.get("file")
    room = request.form.get("room")

    if not file:
        return "400", 400

    filename = secure_filename(file.filename)
    save_path = os.path.join("uploads", filename)
    file.save(save_path)

    # Room に通知
    socketio.emit("new_file", {"filename": filename}, room=room)
    return "OK"


#=========================================================================================================



# -------------------------------
# アプリ起動
# -------------------------------
import os
if __name__ == "__main__":
    init_db()
    import os
    port = int(os.environ.get("PORT", 5000))
    socketio.run(app, host="0.0.0.0", port=port, debug=False)

