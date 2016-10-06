import RPi.GPIO as GPIO
import urllib.request
import json
from time import sleep, localtime, strftime

def notifyCh(status):
    if status == "open":
        msg = "하우스문이 열렸습니다."
    elif status == "close":
        msg = "하우스문이 닫혔습니다."
    else:
        return False

    data = {
        'chat_id':'CHATID', #텔레그램 채널 ID(관리자 권한 필요)
        'text':msg
    }

    req = urllib.request.Request('https://api.telegram.org/botTOKEN/SendMessage') #Telegram bot API의 sendMessage기능
    req.add_header('Content-Type', 'application/json; charset=utf-8')
    response = urllib.request.urlopen(req, json.dumps(data).encode('utf-8')).read()
    result = json.loads(response.decode('utf-8'))

    urllib.request.urlopen('http://anonymous.nflint.com/taemen/RecordData.php?authkey=AUTHID&type=door-&value=' + status)

if __name__ == '__main__':
    SW  = 4 #BCM 4 / pin 7
    flag = False

    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    GPIO.setup(SW,GPIO.IN,GPIO.PUD_UP)

    while True:
        sleep(0.2)
        if GPIO.input(SW) == False and flag == False:
            print("door's opened!")
            notifyCh("open")
            flag = not flag
        elif GPIO.input(SW) == True and flag == True:
            print("door's closed!")
            notifyCh("close")
            flag = not flag
