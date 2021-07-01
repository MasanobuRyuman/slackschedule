import requests
import schedule

TOKEN = 'xoxb-1970271611367-2173804107493-s77NYSVYmAQkk5SsNLWDxUro'
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

sl("koko")
