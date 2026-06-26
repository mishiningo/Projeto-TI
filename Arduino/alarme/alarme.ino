#include <WiFi101.h>
#include <ArduinoHttpClient.h>
#include <SPI.h>
#include <WiFiUdp.h> //Pré-instalada com o Arduino IDE
#include <TimeLib.h>
#include <NTPClient.h>

//Declaração dos pinos dos Leds
const int green = 14;
const int yellow = 13;
const int red = 12;

//Declaração do pino do buzzer - Pino 2 por conta de PWM
const int buzzer = 2;

//Declaração dos pinos do HC-Sr04 (Emissão e receção)
const int trigPin = 9;
const int echoPin = 10;

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

  //Inicializa Buzzer desligado
  noTone(buzzer);

  //HC-Sr04
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
}

void loop() {
  
  //Buzzer desligado ao fim de cada iteração
  noTone(buzzer);

  String estado = get_estado();

  char datahora[20];

  //Alarme desativado -> Led amarela ligada
  if(estado == "Desativado"){
    digitalWrite(green, LOW);
    digitalWrite(yellow, HIGH);
    digitalWrite(red, LOW);
  }else if(estado == "Ativo"){ //Alarme ligado -> Led verde    
      digitalWrite(yellow, LOW);
      digitalWrite(red, LOW);
      digitalWrite(green, HIGH);

      float distancia = get_distancia();
      //Alarme acionado
      if(distancia < 30){
          //Primeira coisa a ser feita: atualizar dashboard
          update_time(datahora);
          estado = "Acionado";
          post2api(estado, datahora, "Arduino");
        //Enquanto o alarme não for desativado ficará sempre em estado acionado.
        while(estado != "Desativado" && estado != "Desativado30"){
          //Alarme acionado -> Led vermelha a piscar e buzzer a tocar
          digitalWrite(green, LOW);
          digitalWrite(yellow, LOW);
          digitalWrite(red, HIGH);
          tone(buzzer, 2500);
          //Chama-se o estado para averiguar se o alarme não foi desativado
          estado = get_estado();
          delay(300);
        }
        return;
      }
  }else if(estado == "Desativado30"){
    //Desliga-se o alarme somente por 30s
    digitalWrite(green, LOW);
    digitalWrite(yellow, HIGH);
    digitalWrite(red, LOW);
    
    //milis devolve o intervalo de tempo desde que a placa foi ligada
    //UL é um sufixo para unsigned long int 
    unsigned long inicio = millis();
    // Ajuste para 25s pois tem-se em conta o tempo de comunicação + iteração
    while (millis() - inicio < 25000UL){
      //Desativado por 30s -> luzes amarelas a piscar
      digitalWrite(green, LOW);
      digitalWrite(yellow, LOW);
      digitalWrite(red, LOW);
      delay(500);
      digitalWrite(green, LOW);
      digitalWrite(yellow, HIGH);
      digitalWrite(red, LOW);
      
      //Verificação do estado do alarme a cada segundo
      //O mesmo pode ter sido ligado novamente durante estes 30s
      estado = get_estado();
      if(estado != "Desativado30"){
        break;
      }
    }
    if(estado == "Desativado30"){
      //Ao fim dos 30s o alarme tem de voltar a estar ativo
      //Caso após a iteração ainda esteja como "Desativado30", ele volta à ativo
      estado = "Ativo";
      update_time(datahora);
      post2api(estado, datahora, "Arduino");
    }
  }else{ 
    // Caso de erro -> Led amarela ligada 
    digitalWrite(green, LOW);
    digitalWrite(yellow, HIGH);
    digitalWrite(red, LOW);
  }  
  
}

void update_time(char *datahora){
  clienteNTP.update();
  unsigned long epochTime = clienteNTP.getEpochTime();
  sprintf(datahora, "%02d-%02d-%02d %02d:%02d:%02d", year(epochTime), month(epochTime), day(epochTime), hour(epochTime), minute(epochTime), second(epochTime));
}

void post2api(String estado, String data, String origem){
  //Definição de para onde enviar
  String URLPath = "/ti/ti061/ProjetoTI/API/api.php"; 
  //Definição dos dados a enviar e construção do body a enviar
  String contentType = "application/x-www-form-urlencoded";
  String body = "estado="+estado+"&hora="+data+"&origem="+origem;
  //Envio por POST
  clienteHTTP.post(URLPath, contentType, body);
  //Confirmação de que foi enviado
  Serial.println("----Confirmação Post2API---------");
  Serial.print("Response status code: ");
  Serial.println(clienteHTTP.responseStatusCode());
  Serial.print("Response body: ");
  Serial.println(clienteHTTP.responseBody());
}

String get_estado(void) {

  String URLPath = "/ti/ti061/ProjetoTI/API/api.php?origem=Arduino";

  clienteHTTP.get(URLPath);

  int statusCode = clienteHTTP.responseStatusCode();
  String resposta = clienteHTTP.responseBody();

  //Se o pedido falhou, devolve -1 
  if (statusCode != 200) {
    Serial.print("ERRO ao obter estado: ");
    Serial.println(resposta);
    return "Erro";
  }

  //O formato da resposta é "estado;hora"
  //indexOf detecta ";" e o utiliza como separador de strings, logo [0] em relação ao separador é o indice do estado
  int separador = resposta.indexOf(';');
  String estadoRecebido = resposta.substring(0, separador);

  return estadoRecebido;

}

float get_distancia(void){
  
  float duracao, distancia;
  
  //Realiza a emissão
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);

  //Realiza a receção
  duracao = pulseIn(echoPin, HIGH);
  
  //Calculo da distancia
  distancia = (duracao*.0343)/2;
  
  //Prints para debug
  Serial.print("Distancia: ");
  Serial.println(distancia);

  return distancia;
}
