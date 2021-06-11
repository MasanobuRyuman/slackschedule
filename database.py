import sqlite3

dbname = 'main.db'
# DBを作成する（既に作成されていたらこのDBに接続する）
conn = sqlite3.connect(dbname)
cur = conn.cursor()
# DBのテーブルを作成している。
cur.execute("create table user(name text, password text);")
cur.execute("create table contentTime(name text,time text,content text,beforetime text,cou INTEGER);")
conn.commit()
conn.close()
