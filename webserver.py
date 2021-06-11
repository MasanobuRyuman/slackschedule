from cgi import FieldStorage
from http.server import HTTPServer, SimpleHTTPRequestHandler
import re
from html.parser import HTMLParser
from bs4 import BeautifulSoup
import slack
import sqlite3

import datetime

dbname = 'main.db'
# DBを作成する（既に作成されていたらこのDBに接続する）
conn = sqlite3.connect(dbname)
cur = conn.cursor()
# DBのテーブルを作成している。
cur.execute('INSERT INTO user(name,password) values(?,?)',("ryuman","oomo"))

cur.execute("select * from contentTime")
for row in cur:  # レコードを出力する
    print (row)

cur.execute("select * from user")
for row in cur:  # レコードを出力する
    print (row)

with open('login.html','r') as f:
    soup = BeautifulSoup(f.read(), 'html.parser')
with open("form.html",'r') as f:
    form_file = f.read()

with open('main.html', 'r') as f:
    main_file = f.read()

with open('registration.html','r') as f:
    registration_file = f.read()


class OriginalHTTPRequestHandler(SimpleHTTPRequestHandler):
    def do_GET(self):


        super().do_GET()

    def do_POST(self):
        #最初の画面でログインが押されたら
        if re.search('login$',self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
                fp = self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD':'POST'})

            formtag = soup.find(id="lg")
            attentiontag = soup.find(id="attention")
            formtag.attrs["action"] = "loginIn"
            attentiontag.string = ""
            soup
            self.wfile.write(soup.encode('UTF-8'))
            return None

        #最初の画面で新規登録が押されたら
        if re.search("newLogin$",self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
                fp = self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD':'POST'})
            formtag = soup.find(id="lg")
            attention = soup.find(id="attention")
            formtag.attrs["action"] = "loginNew"
            attention.string = ""
            soup
            self.wfile.write(soup.encode('UTF-8'))
            return None

        if re.search('registration.html$', self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
                fp = self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD':'POST'})
            global date_form
            date_form=form['date'].value
            html = registration_file.format(
                setDate =date_form
            )
            print(date_form)
            self.wfile.write(html.encode('UTF-8'))

        if re.search('action.html$',self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
                fp = self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD':'POST'})
            content_form = form['contentfield'].value
            content_time = form["time"].value
            beforetime=form["beforeTime"].value
            date_form=date_form.replace("/","-")
            dte=date_form+" "+content_time
            timetime = datetime.datetime.strptime(dte, '%Y-%m-%d %H:%M')

            content_beforetime = timetime - datetime.timedelta(hours = int(beforetime[:2]))
            content_beforetime = timetime - datetime.timedelta(minutes = int(beforetime[3:]))

            cur.execute('INSERT INTO contentTime(name,time,content,beforeTime,cou) values(?,?,?,?,?)',("Ryuman",timetime,content_form,content_beforetime,0))

            conn.commit()

            self.wfile.write(main_file.encode('UTF-8'))

        if re.search("loginIn$",self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
                fp = self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD':'POST'})
            user_form = form["user"].value
            password_form = form['password'].value

            cur.execute("SELECT * FROM user WHERE name = ? AND password = ?", (user_form,password_form))
            print(cur.fetchone())
            if cur.fetchone() != None:
                self.wfile.write(main_file.encode('UTF-8'))
            else:
                formtag = soup.find("form")
                attentiontag = soup.find(id="attention")
                formtag.attrs["action"] = "loginIn"
                attentiontag.string = "名前かパスワードが間違っています。"
                soup
                self.wfile.write(soup.encode('UTF-8'))


        if re.search("loginNew$",self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
               fp = self.rfile,
               headers = self.headers,
               environ = {'REQUEST_METHOD':'POST'}
            )
            user_form = form["user"].value
            password_form = form['password'].value
            print(user_form)
            cur.execute("SELECT * FROM user WHERE name = ?", (user_form,))
            if cur.fetchone() == None:
                cur.execute('INSERT INTO user(name,password) values(?,?)',(user_form,password_form))
                conn.commit()
                self.wfile.write(main_file.encode('UTF-8'))
            else:
                formtag = soup.find("form")
                attentiontag = soup.find(id="attention")
                formtag.attrs["action"] = "loginNew"
                attentiontag.string = "すでに同じ名前の人がいます。"
                soup
                self.wfile.write(soup.encode('UTF-8'))
            return None
def run(server_class=HTTPServer, handler_class=OriginalHTTPRequestHandler):
    server_address = ('', 8000)
    httpd = server_class(server_address, handler_class)
    httpd.serve_forever()


if __name__ == '__main__':
    run()

conn.close()
