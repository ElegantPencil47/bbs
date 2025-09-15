from flask import Flask, request, redirect, render_template, url_for
import sqlite3
from datetime import datetime

app = Flask(__name__)

# DB初期化
def init_db():
    conn = sqlite3.connect("bbs.db")
    c = conn.cursor()
    c.execute("CREATE TABLE IF NOT EXISTS threads (id INTEGER PRIMARY KEY, title TEXT, created_at TEXT)")
    c.execute("CREATE TABLE IF NOT EXISTS posts (id INTEGER PRIMARY KEY, thread_id INTEGER, name TEXT, message TEXT, created_at TEXT)")
    conn.commit()
    conn.close()

@app.route("/")
def index():
    # スレッド一覧
    conn = sqlite3.connect("bbs.db")
    c = conn.cursor()
    c.execute("SELECT id, title, created_at FROM threads ORDER BY id DESC")
    threads = c.fetchall()
    conn.close()
    return render_template("index.html", threads=threads)

@app.route("/thread/new", methods=["POST"])
def new_thread():
    title = request.form["title"]
    name = request.form["name"] or "名も無き者"
    message = request.form["message"]
    conn = sqlite3.connect("bbs.db")
    c = conn.cursor()
    
    try:
        # 1. threadsテーブルにスレッドを挿入
        c.execute("INSERT INTO threads (title, created_at) VALUES (?, ?)", (title, datetime.now().isoformat()))
        new_thread_id = c.lastrowid # 新しく生成されたスレッドIDを取得
        
        # 2. postsテーブルに最初の投稿を挿入
        c.execute("INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
                  (new_thread_id, name, message, datetime.now().isoformat()))
        
        conn.commit()
    except sqlite3.Error as e:
        conn.rollback() # エラー時はロールバック
        print(f"Database error: {e}")
        # エラーページにリダイレクトするなど、適切な処理を追加
        return redirect(url_for("index"))
    finally:
        conn.close()
    
    # 3. 新しく作成したスレッドページへリダイレクト
    return redirect(url_for("thread", thread_id=new_thread_id))

@app.route("/thread/<int:thread_id>", methods=["GET", "POST"])
def thread(thread_id):
    conn = sqlite3.connect("bbs.db")
    c = conn.cursor()
    if request.method == "POST":
        name = request.form["name"] or "名も無き者"
        message = request.form["message"]
        try:
            c.execute("INSERT INTO posts (thread_id, name, message, created_at) VALUES (?, ?, ?, ?)",
                      (thread_id, name, message, datetime.now().isoformat()))
            conn.commit()
        except sqlite3.Error as e:
            conn.rollback()
            print(f"Database error: {e}")
        finally:
            conn.close()
            # リダイレクトして重複投稿を防止
            return redirect(url_for("thread", thread_id=thread_id))
    
    # GETリクエストの場合、またはPOST後に再読み込みする場合
    c.execute("SELECT id, title FROM threads WHERE id=?", (thread_id,))
    thread = c.fetchone()
    c.execute("SELECT name, message, created_at FROM posts WHERE thread_id=? ORDER BY id ASC", (thread_id,))
    posts = c.fetchall()
    conn.close()
    return render_template("thread.html", thread=thread, posts=posts)

if __name__ == "__main__":
    init_db()
    app.run(debug=True)

