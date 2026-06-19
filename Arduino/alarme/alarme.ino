#include <WiFi101.h>
#include <ArduinoHttpClient.h>
#include <SPI.h>
#include <WiFiUdp.h> //Pré-instalada com o Arduino IDE
#include <TimeLib.h>
#include <NTPClient.h>

//Declaração dos pinos dos Leds
int green = 14;
int yellow = 13;
int red = 12;

//Declaração do pino do buzzer
int buzzer = 6;

//Definição dos dados WiFi
char SSID[] = "labs";
char PASS_WIFI[] = "1nv3nt@r2023_IPLEIRIA";

//Definição do servidor de envio
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
  
  //Inicialização do NTP 
  clienteNTP.begin();
  //Inicialização dos pinos do led
  pinMode(red, OUTPUT);
  pinMode(yellow, OUTPUT);
  pinMode(green, OUTPUT);

  //Inicialização do pino do buzzer
  pinMode(buzzer, OUTPUT);

  //Inicializam com a led amarela ligadaS
  digitalWrite(yellow, HIGH);
  digitalWrite(red, LOW);
  digitalWrite(green, LOW);
}

void loop() {
  
  digitalWrite(buzzer, LOW);
  int estado = get_estado();

  //Alarme desativado -> Led verde ligada
  if(estado == 0){
    digitalWrite(green, HIGH);
    digitalWrite(yellow, LOW);
    digitalWrite(red, LOW);
  }else if(estado == 1){ //Alarme ligado -> Led vemelha    
      digitalWrite(yellow, LOW);
      digitalWrite(red, HIGH);
      digitalWrite(green, LOW);

      /*  ESPAÇO DESTINADO AO SENSOR HC-SR04
        FUNÇÃO get_distancia
      */

      //Alarme acionado
      if(1 == 1){
        //Enquanto o alarme não for desativado ficará sempre em estado acionado.
        while(estado == 1 || estado == -1){
          //Alarme acionado -> Led vermelha a piscar e buzzer a tocar
          digitalWrite(green, LOW);
          digitalWrite(yellow, LOW);
          digitalWrite(red, HIGH);
          digitalWrite(buzzer, HIGH);
          delay(300);
          digitalWrite(red, LOW);
          estado = get_estado();
          digitalWrite(buzzer, LOW);
        }
      }
  }else{ // Caso de erro -> Led amarela ligada 
    digitalWrite(green, LOW);
    digitalWrite(yellow, HIGH);
    digitalWrite(red, LOW);
  }  

  //Declaração e atualização da data e hora
  char datahora[20];
  update_time(datahora);

  //Função que envia dados para a api
  String stringEstado;
  if(estado == 1){
    stringEstado = "Ativo";
  }else if (estado == 0){
    stringEstado = "Desativado";
  }else{
    stringEstado = "N/A";
  }
  post2api("Alarme", stringEstado, datahora);
}

void update_time(char *datahora){
  clienteNTP.update();
  unsigned long epochTime = clienteNTP.getEpochTime();
  sprintf(datahora, "%02d-%02d-%02d %02d:%02d:%02d", year(epochTime), month(epochTime), day(epochTime), hour(epochTime), minute(epochTime), second(epochTime));
}

void post2api(String nome, String estado, String data){
  //Definição de para onde enviar
  String URLPath = "/ti/ti061/ProjetoTI/API/api.php"; 
  //Definição dos dados a enviar e construção do body a enviar
  String contentType = "application/x-www-form-urlencoded";
  String body = "nome="+nome+"&estado="+estado+"&hora="+data;
  //Envio por POST
  clienteHTTP.post(URLPath, contentType, body);
  //Confirmação de que foi enviado
  Serial.println("----Confirmação Post2API---------");
  Serial.print("Response status code: ");
  Serial.println(clienteHTTP.responseStatusCode());
  Serial.print("Response body: ");
  Serial.println(clienteHTTP.responseBody());
  delay(5000);
}

int get_estado(void) {

  String URLPath = "/ti/ti061/ProjetoTI/API/api.php?nome=Alarme";

  clienteHTTP.get(URLPath);

  int statusCode = clienteHTTP.responseStatusCode();
  String resposta = clienteHTTP.responseBody();

  //Se o pedido falhou, devolve -1 
  if (statusCode != 200) {
    Serial.print("ERRO ao obter estado: ");
    Serial.println(resposta);
    return -1;
  }

  //O formato da resposta é "estado;hora"
  //indexOf detecta ";" e o utiliza como separador de strings, logo [0] em relação ao separador é o indice do estado
  int separador = resposta.indexOf(';');
  String estadoRecebido = resposta.substring(0, separador);

  //Converte o texto recebido em 0 ou 1
  if (estadoRecebido == "Ativo") {
    return 1;
  } else {
    return 0;
  }
}