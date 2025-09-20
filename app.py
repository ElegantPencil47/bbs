from flask import Flask, request, redirect, render_template, url_for, g
from flask import make_response
from markupsafe import Markup, escape
import sqlite3
from datetime import datetime

DATABASE = "bbs.db"

# Flaskアプリケーションの初期化
app = Flask(__name__)
# テンプレート変数やデータベース接続管理のためのgオブジェクトを定義する前に、appを定義
app.secret_key = 'your_super_secret_key_here' # クッキーとセッションに必要

def get_db():
    db = getattr(g, "_database", None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
        db.row_factory = sqlite3.Row  # 列名でアクセスできるようにする
    return db

@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, "_database", None)
    if db is not None:
        db.close()

# DB初期化
def init_db():
    with app.app_context():
        db = get_db()
        c = db.cursor()
        c.execute("CREATE TABLE IF NOT EXISTS threads (id INTEGER PRIMARY KEY, title TEXT, created_at TEXT)")
        c.execute("CREATE TABLE IF NOT EXISTS posts (id INTEGER PRIMARY KEY, thread_id INTEGER, name TEXT, message TEXT, created_at TEXT)")
        db.commit()

@app.route("/set_theme", methods=["POST"])
def set_theme():
    theme = request.form["theme"]
    resp = make_response(redirect("/"))
    resp.set_cookie("theme", theme)
    return resp

@app.route("/")
def index():
    theme = request.cookies.get("theme", "default")
    db = get_db()
    c = db.cursor()
    c.execute("SELECT id, title, created_at FROM threads ORDER BY id DESC")
    threads = c.fetchall()
    return render_template("index.html", threads=threads, theme=theme)

@app.route("/thread/new", methods=["POST"])
def new_thread():
    title = request.form["title"]
    name = request.form["name"] or "名も無き者"
    message = request.form["message"]
    db = get_db()
    c = db.cursor()
    
    try:
        c.execute("INSERT INTO threads (title, created_at) VALUES (?, ?)", (title, datetime.now().isoformat()))
        new_thread_id = c.lastrowid
        
        c.execute("INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
                  (new_thread_id, name, message, datetime.now().isoformat()))
        
        db.commit()
    except sqlite3.Error as e:
        db.rollback()
        print(f"Database error: {e}")
        return redirect(url_for("index"))
    
    return redirect(url_for("thread", thread_id=new_thread_id))

@app.template_filter("nl2br")
def nl2br(s):
    if s is None:
        return ""
    # エスケープした上で改行ごとに分割し、<br>で結合
    return Markup("<br>").join(escape(s).splitlines())

@app.route("/thread/<int:thread_id>", methods=["GET", "POST"])
def thread(thread_id):
    db = get_db()
    c = db.cursor()
    if request.method == "POST":
        name = request.form["name"] or "名も無き者"
        message = request.form["message"]
        try:
            c.execute("INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
                      (thread_id, name, message, datetime.now().isoformat()))
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

if __name__ == "__main__":
    init_db()
    app.run(debug=True)
