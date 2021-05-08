import requests
import schedule

TOKEN = 'xoxb-1970271611367-2021580255234-1hAW5CH3ZXVT34R2GISjZHyk'
CHANNEL = 'スケジュールのお知らせ'

url = "https://slack.com/api/chat.postMessage"
headers = {"Authorization": "Bearer "+TOKEN}
def sl(sentContent):
    data  = {
       'channel': CHANNEL,
       'text': sentContent
    }
    r = requests.post(url, headers=headers, data=data)
    print("return ", r.json())
    return schedule.CancelJob
