from cgi import FieldStorage
from http.server import HTTPServer, SimpleHTTPRequestHandler
import re
from html.parser import HTMLParser
from bs4 import BeautifulSoup
import slack
import schedule
import datetime
import time
import asyncio

dateDictionary={}
date_form=0


async def main():

    while True:
        print("kitayo")
        await asyncio.sleep(1)

loop = asyncio.get_event_loop()
asyncio.ensure_future(main())


with open('index.html', 'r') as f:
    index_file = f.read()

with open('registration.html','r') as f:
    soup = f.read()


class OriginalHTTPRequestHandler(SimpleHTTPRequestHandler):
    def do_GET(self):
        print(self.path)
        if re.search('spical.html$', self.path) != None:
            self.send_response(200)
            self.end_headers()

            htmltag = soup.new_tag('html')
            headtag = soup.new_tag("head")
            bodytag = soup.new_tag("body")
            sumple = soup.new_tag("h1")
            sumple.string="slack"
            htmltag.append(headtag)
            htmltag.append(bodytag)
            bodytag.append(sumple)

            html=htmltag
            soup
            self.wfile.write(html.encode('UTF-8'))



            return None
        # 200 コードとヘッダ終了コードを送ってから本文を送り出す
        else:


            super().do_GET()

    def do_POST(self):
        if re.search('registration.html$', self.path) != None:
            self.send_response(200)
            self.end_headers()
            form = FieldStorage(
                fp = self.rfile,
                headers=self.headers,
                environ={'REQUEST_METHOD':'POST'})
            global date_form
            date_form=form['date'].value
            html = soup.format(
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
            if date_form in dateDictionary:
                dateDictionary[date_form].append([content_time,content_form])
            else:
                dateDictionary[date_form]=[[content_time,content_form]]
            print(dateDictionary)

            self.wfile.write(index_file.encode('UTF-8'))

def today():
    dt_now = datetime . datetime . now ( )
    print(dt_now)
    todaydate=str(dt_now.year) + "/" + str(dt_now.month )+ "/" + str(dt_now.day)
    if todaydate in dateDictionary:
        for i in dateDictionary[todaydate]:
            print("koko"+i[1])
            print(i[0])
            schedule.every().day.at(i[0]).do(slack.sl(str(i[1])))




def run(server_class=HTTPServer, handler_class=OriginalHTTPRequestHandler):
    server_address = ('', 8001)
    httpd = server_class(server_address, handler_class)
    httpd.serve_forever()


if __name__ == '__main__':
    run()

while True:
    today = datetime . datetime . now ( )
    today.strftime("%Y/%m/%d")
    print(today)
    if today in dateDictionary:
        print("kikit")
