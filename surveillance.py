import datetime
import slack
import sqlite3
import time

dbname = 'main.db'
# DBを作成する（既に作成されていたらこのDBに接続する）
conn = sqlite3.connect(dbname)
cur = conn.cursor()

while True:
    now = datetime.datetime.now()
    now_year = now.year
    now_month = now.month
    now_day = now.day
    now_hour = now.hour
    now_minute = now.minute
    now_month = "0" + str(now_month)
    now_day = "0" + str(now_day)
    now_hour = "0" + str(now_hour)
    now_minute = "0" + str(now_minute)
    now_month = now_month[-2:]
    now_day = now_day[-2:]
    now_hour = now_hour[-2:]
    now_minute = now_minute[-2:]
    now_time = str(now_year) + "-" + str(now_month) + "-" + str(now_day) + " " + str(now_hour) + ":" + str(now_minute) + ":"+ "00"
    print(now_time)

    sql = 'select * from contentTime where name=? and beforetime=? and cou=0'
    for row in cur.execute(sql,(now_day,now_time)):
        slack.sl(row)
        cur.execute("update contentTime set cou=? where name=? and beforetime=?",(1,userName,now_time))
    time.sleep(10)
