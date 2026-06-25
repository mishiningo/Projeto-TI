
import requests
import time
from gpiozero import LED
from time import sleep
import datetime
import RPi.GPIO as gpio

	
def post2API(nome, estado):
	agora = datetime.datetime.now()
	payload = {'nome': nome , 'estado': estado, 'hora': agora.strftime("%Y-%m-%d %H:%M:%S"), 'origem': 'Raspberry' }
	r = requests.post('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/api.php', data=payload)
	if r.status_code == 200:
		print("Pedido bem sucedido")
	else:
		print(r.text)
		
def getEstado():
	r = requests.get('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/api.php?nome=alarme&origem=Raspberry')
	if r.status_code != 200:
		print(r.text)
		return 
	return r.text.strip().split(";")[0] #funcao para limpar espaços ou quebra de linhas, funcao para dividir texto em array com base no ;  , devoluçao do primeiro elemento do array(estado)
	
	
		
def switchEstado():
	estado=getEstado()
	if estado=='Desativado' or estado=='Desativado30':
		post2API('alarme', 'Ativo')
	elif estado=='Ativo' or estado=='Acionado':
		post2API('alarme', 'Desativado')
	sleep(0.3) #pequeno delay para o caso do botao acionar mais de uma vez

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
		
		input_value = gpio.input(27)
		
		if input_value == True:
			print('The button has been pressed...')
			switchEstado()
			while input_value == True:
				input_value = gpio.input(27)
		estado = getEstado()
		print(estado)
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
	except KeyboardInterrupt:
		print('\n O script foi interrompido pelo Utilizador.')
		ledVerde.off()
		ledVermelho.off()
		ledAmarelo.off()
		break
	except Exception as e:
		print('Erro inesperado:', e)
		ledVerde.off()
		ledVermelho.off()
		ledAmarelo.off()
		break
