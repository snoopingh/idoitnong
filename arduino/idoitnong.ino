#include "DHT.h"
#include <SPI.h>
#include <Ethernet.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define LED_GREEN_PIN 0
#define LED_YELLOW_PIN 1
#define LED_RED_PIN 2
#define BUZZZER_PIN 3
#define RAIN_SENSOR_PIN 4
#define MAGNETIC_SENSOR_PIN 5
#define DS18B20_IN_PIN 6
#define DS18B20_OUT_PIN 7
#define DHT22_IN_PIN 8

#define L_N 0
#define L_G 1
#define L_Y 2
#define L_R 3
#define L_GY 4
#define L_GR 5
#define L_YR 6
#define L_GYR 7

DHT DHT22_IN(DHT22_IN_PIN, DHT22);
OneWire oneWire_in(DS18B20_IN_PIN);
OneWire oneWire_out(DS18B20_OUT_PIN);
DallasTemperature DS18B20_IN(&oneWire_in);
DallasTemperature DS18B20_OUT(&oneWire_out);

byte MAC[] = {0xDA, 0xAA, 0xBE, 0xEF, 0xFE, 0x44};
IPAddress ip(192, 168, 1, 177);
EthernetClient client;
char server_address[] = "anonymous.nflint.com";

void runMagSensor(byte *mss)
{
  if(*mss = digitalRead(MAGNETIC_SENSOR_PIN))
  {
    tone(BUZZZER_PIN,1397);//3520
    ledControl(L_Y);
    delay(100);
    noTone(BUZZZER_PIN);
    ledControl(L_N);
    delay(100);
  }
  else
  {
    noTone(BUZZZER_PIN);
    ledControl(L_N);
    delay(200);
  }
}

void ledControl(char mode)
{
  switch(mode)
  {
    case L_N: digitalWrite(LED_GREEN_PIN, LOW); digitalWrite(LED_YELLOW_PIN, LOW); digitalWrite(LED_RED_PIN, LOW); break;
    case L_G: digitalWrite(LED_GREEN_PIN, HIGH); digitalWrite(LED_YELLOW_PIN, LOW); digitalWrite(LED_RED_PIN, LOW); break;
    case L_Y: digitalWrite(LED_GREEN_PIN, LOW); digitalWrite(LED_YELLOW_PIN, HIGH); digitalWrite(LED_RED_PIN, LOW); break;
    case L_R: digitalWrite(LED_GREEN_PIN, LOW); digitalWrite(LED_YELLOW_PIN, LOW); digitalWrite(LED_RED_PIN, HIGH); break;
    case L_GY: digitalWrite(LED_GREEN_PIN, HIGH); digitalWrite(LED_YELLOW_PIN, HIGH); digitalWrite(LED_RED_PIN, LOW); break;
    case L_GR: digitalWrite(LED_GREEN_PIN, HIGH); digitalWrite(LED_YELLOW_PIN, LOW); digitalWrite(LED_RED_PIN, HIGH); break;
    case L_YR: digitalWrite(LED_GREEN_PIN, LOW); digitalWrite(LED_YELLOW_PIN, HIGH); digitalWrite(LED_RED_PIN, HIGH); break;
    case L_GYR: digitalWrite(LED_GREEN_PIN, HIGH); digitalWrite(LED_YELLOW_PIN, HIGH); digitalWrite(LED_RED_PIN, HIGH); break;
  }
}

void setup() {
  //Serial.begin(9600);
  //Serial.println("Service Start !");
  
  pinMode(MAGNETIC_SENSOR_PIN,INPUT);
  pinMode(RAIN_SENSOR_PIN,INPUT);
  pinMode(BUZZZER_PIN, OUTPUT);
  pinMode(LED_RED_PIN, OUTPUT);
  pinMode(LED_YELLOW_PIN, OUTPUT);
  pinMode(LED_GREEN_PIN, OUTPUT);
  
  ledControl(L_GYR);

  if (Ethernet.begin(MAC) == 0) {
        Ethernet.begin(MAC, ip);
    for(;;)
     ;
  }
  
  ledControl(L_N);
  delay(1000);
  
  ledControl(L_G);
  tone(BUZZZER_PIN,1047);
  delay(200);
  ledControl(L_Y);
  tone(BUZZZER_PIN,1319);
  delay(200);
  ledControl(L_R);
  tone(BUZZZER_PIN,1568);
  delay(200);
  ledControl(L_N);
  noTone(BUZZZER_PIN);
  delay(200);
  ledControl(L_R);
  tone(BUZZZER_PIN,1568);
  delay(200);
  ledControl(L_Y);
  tone(BUZZZER_PIN,1319);
  delay(200);
  ledControl(L_G);
  tone(BUZZZER_PIN,1047);
  delay(200);
  ledControl(L_N);
  noTone(BUZZZER_PIN);
  delay(100);
  ledControl(L_GYR);
  tone(BUZZZER_PIN,2093);
  delay(100);
  ledControl(L_N);
  noTone(BUZZZER_PIN);
  delay(100);
  ledControl(L_GYR); 
  tone(BUZZZER_PIN,2093);
  delay(100);
  noTone(BUZZZER_PIN);
}


void loop() {
  ledControl(L_N);
  
  DS18B20_IN.requestTemperatures();
  DS18B20_OUT.requestTemperatures();
  
  byte mss;
  float dhit = DHT22_IN.readTemperature();       //DHT22_IN 온도 읽기
  float dhih = DHT22_IN.readHumidity();          //DHT22_IN 습도 읽기
  float dsit = DS18B20_IN.getTempCByIndex(0);    //DS18B20_IN 온도 읽기
  float dsot = DS18B20_OUT.getTempCByIndex(0);   //DS18B20_OUT 온도 읽기
  byte rss = digitalRead(RAIN_SENSOR_PIN);       //우적센서 상태 읽기 [Default : 1]
  String sMsg = "";
  
  if (isnan(dhit) || isnan(dhih))
  {
    ledControl(L_GY);
    delay(1000);
    return;
  }
  else if(dsit == -127.00)
  {
    ledControl(L_GR);
    delay(1000);
    return;
  }
  else if (dsot == -127.00)
  {
    ledControl(L_YR);
    delay(1000);
    return;
  }
  
  for(int i = 0; i < 40; i ++) runMagSensor(&mss);

  ledControl(L_R);
  delay(500);
  ledControl(L_Y);
  delay(500);

  if(client.connect(server_address, 80))
  {
    sMsg = sMsg + "GET /farm/act.php?" + "dhit=" + dhit + "&dhih=" + dhih + "&dsit=" + dsit + "&dsot=" + dsot + "&mss=" + mss + "&rss=" + rss + " HTTP/1.0";
    client.println(sMsg);
    client.println("Host: anonymous.nflint.com");
    client.println("Connection: close");
    client.println();
    ledControl(L_G);
    //tone(BUZZZER_PIN,1760);
    delay(400);
    //tone(BUZZZER_PIN,1397);
    delay(600);
    //noTone(BUZZZER_PIN);
    client.stop();
  }
  else
  {
    //tone(BUZZZER_PIN,1760);
    delay(475);
    //noTone(BUZZZER_PIN);
    delay(50);
    //tone(BUZZZER_PIN,1760);
    delay(475);
    //noTone(BUZZZER_PIN);
    ledControl(L_R);
  }
}
