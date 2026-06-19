#include <WiFi101.h>
#include <ArduinoHttpClient.h>
#include <SPI.h>
#include <WiFiUdp.h> //Pré-instalada com o Arduino IDE
#include <TimeLib.h>
#include <NTPClient.h>

char SSID[] = "labs";
char PASS_WIFI[] = "1nv3nt@r2023_IPLEIRIA";

char URL[] = "iot.dei.estg.ipleiria.pt";
int PORTO = 80;

WiFiClient clienteWifi;
HttpClient clienteHTTP = HttpClient(clienteWifi, URL, PORTO);

WiFiUDP clienteUDP;
//Servidor de NTP do IPLeiria: ntp.ipleiria.pt
//Fora do IPLeiria servidor: 0.pool.ntp.org
char NTP_SERVER[] = "ntp.ipleiria.pt";
NTPClient clienteNTP(clienteUDP, NTP_SERVER, 3600);

void setup() {
  //Conexão à labs
  Serial.begin(115200);
  while (!Serial);
  WiFi.begin(SSID, PASS_WIFI);
  while(WiFi.status() != WL_CONNECTED){
    Serial.println(".");
    delay(500);
  }
  Serial.println((IPAddress)WiFi.localIP());
  Serial.println((IPAddress)WiFi.subnetMask());
  Serial.println((IPAddress)WiFi.gatewayIP());
  Serial.println(WiFi.RSSI());
  
  //Inicialização do NTP para data e hora
  clienteNTP.begin();
}

void loop() {
  //Definição de para onde enviar
  String URLPath = "/ti/ti061/ProjetoTI/API/api.php"; 
  //Definição dos dados a enviar
  String contentType = "application/x-www-form-urlencoded";

  char datahora[20];
  update_time(datahora);
  Serial.print("Data Atual: ");
  Serial.println(datahora);

  String enviaNome = "Alarme";
  String enviaEstado = "Ativo";
  String body = "nome="+enviaNome+"&estado="+enviaEstado+"&hora="+datahora;
  //Envio por POST
  clienteHTTP.post(URLPath, contentType, body);
  //Confirmação de que foi enviado
  Serial.print("Response status code: ");
  Serial.println(clienteHTTP.responseStatusCode());
  Serial.print("Response body: ");
  Serial.println(clienteHTTP.responseBody());
  delay(5000);
}

void update_time(char *datahora){
  clienteNTP.update();
  unsigned long epochTime = clienteNTP.getEpochTime();
  sprintf(datahora, "%02d-%02d-%02d %02d:%02d:%02d", year(epochTime), month(epochTime), day(epochTime), hour(epochTime), minute(epochTime), second(epochTime));
}