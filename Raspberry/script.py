import requests
import time
from gpiozero import LED
from time import sleep
import datetime
import RPi.GPIO as gpio

	# Função para realizar o post do botão para a API
def post2API(nome, estado):
	agora = datetime.datetime.now()
	payload = {'nome': nome , 'estado': estado, 'hora': agora.strftime("%Y-%m-%d %H:%M:%S"), 'origem': 'Raspberry' }
	r = requests.post('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/api.php', data=payload)
	if r.status_code == 200:
		print("Pedido bem sucedido")
	else:
		print(r.text)
	
	#Função para receber o estado do alarme através do método GET
def getEstado():
	r = requests.get('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/api.php?nome=alarme&origem=Raspberry')
	if r.status_code != 200:
		print(r.text)
		return 
	#Funcao para limpar espaços ou quebra de linhas, funcao para dividir texto em array com base no ;  , devoluçao do primeiro elemento do array(estado)
	return r.text.strip().split(";")[0] 
	
	
	#Função para alternar o estado do alarme através do botão	
def switchEstado():
	estado=getEstado()
	if estado=='Desativado' or estado=='Desativado30':
		post2API('alarme', 'Ativo')
	elif estado=='Ativo' or estado=='Acionado':
		post2API('alarme', 'Desativado')
	sleep(0.3) #pequeno delay para o caso do botao acionar mais de uma vez

#Declarção dos pinos utilizados para leds + botão
ledVerde=LED(2)
ledVermelho=LED(4)
ledAmarelo=LED(15)		
gpio.setmode(gpio.BCM)
gpio.setup(27,  gpio.IN, pull_up_down=gpio.PUD_UP)

ledAmarelo.on()
ledVerde.off()
ledVermelho.off()
while True:
	try:
		#Recebe valor do botão
		input_value = gpio.input(27)
		#Caso botão tenha sido pressionado -> muda-se o estado do alarme e realiza o post para a API
		if input_value == True:
			print('O botão foi pressionado.')
			switchEstado()
			while input_value == True:
				input_value = gpio.input(27)
		#Recebe-se o estado através da API para representação com a led
		estado = getEstado()
		if estado=='Desativado':
			ledAmarelo.on()
			ledVerde.off()
			ledVermelho.off()
		elif estado=='Acionado':
			ledVermelho.on()
			ledAmarelo.off()
			ledVerde.off()
		elif estado=='Ativo':
			ledVerde.on()
			ledVermelho.off()
			ledAmarelo.off()
		elif estado=='Desativado30':
			ledVerde.off()
			ledVermelho.off()
			ledAmarelo.blink()
		sleep(2)
	#Mensagem para caso de interrupção do utilizador (^C)
	except KeyboardInterrupt:
		print('\n O script foi interrompido pelo Utilizador.')
		ledVerde.off()
		ledVermelho.off()
		ledAmarelo.off()
		break
	#Mensagem para outros casos
	except Exception as e:
		print('Erro inesperado:', e)
		ledVerde.off()
		ledVermelho.off()
		ledAmarelo.off()
		break
