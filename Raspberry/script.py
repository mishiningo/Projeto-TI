import requests
import time
from gpiozero import LED
from time import sleep
import datetime
import RPi.GPIO as gpio
import cv2

#Função para a captura e envio de fotos
def capturar_e_enviar():
		nomeData = str(datetime.datetime.now())
		nomeFicheiro = nomeData.replace(" ", "").replace("-", "_").replace(":","_").replace(".","")
		
		print("A ligar  DroidCam...")
		webcam_url = "http://10.20.228.179:4747/video"
		cap = cv2.VideoCapture(webcam_url)
		#Verificação acerca da ligação da câmera
		if not cap.isOpened():
    		print("Erro: Não foi possível ligar à DroidCam.")
    		return False
		 
		#Espera 3 segundos com a camara ligada para não haver sobrecarga no servidor
		time.sleep(3)
		
		#Captura a imagem final
		ret, frame = cap.read()
		
		# Liberta a camara imediatamente
		cap.release() 

		#Caso retorne algo
		if ret:
			print("Imagem capturada com sucesso! A preparar envio...")
			
			#Codifica o frame do OpenCV (.png/.jpg) diretamente na memoria para JPEG
			#Suceso é true or false, img_bytes é um array com os bytes da imagem
			sucesso_conversao, img_bytes = cv2.imencode('.jpg', frame)
			
			if not sucesso_conversao:
				print("Erro ao converter o frame para JPEG.")
				return False
				
			#Prepara o pacote ficheiros com o nome do campo esperado pelo PHP ('imagem')
			ficheiros = {
				#tobytes converte o array de bytes para bytes puros, necessário para comunicação http
				'imagem': (nomeFicheiro, img_bytes.tobytes(), 'image/jpeg')
			}
			
			try:
				# Envia o ficheiro para o upload.php via POST
				r = requests.post('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/gestorFotos.php', files=ficheiros)
				if r.status_code == 200:
					print("[+] Sucesso: Imagem guardada no servidor do site!")
					return True
				else:
					print(f"[-] Erro no Servidor Web: Codigo {r.status_code}. Resposta: {r.text}")
					return False
			except KeyboardInterrupt:
				print('\n O script foi interrompido pelo Utilizador.')
				return False
			except Exception as e:
				print("Erro ao tentar comunicar com o servidor de upload:", e)
				return False
		else:
			print("Erro: Nao foi possivel ler o frame da DroidCam.")
			return False


	# Função para realizar o post do botão para a API
def post2API(estado):
	agora = datetime.datetime.now()
	payload = {'estado': estado, 'hora': agora.strftime("%Y-%m-%d %H:%M:%S"), 'origem': 'Raspberry' }
	r = requests.post('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/api.php', data=payload)
	if r.status_code == 200:
		print("Pedido bem sucedido")
	else:
		print(r.text)
	
	#Função para receber o estado do alarme através do método GET
def getEstado():
	r = requests.get('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/api.php?origem=Raspberry')
	if r.status_code != 200:
		print(r.text)
		return 
	#Funcao para limpar espaços ou quebra de linhas, funcao para dividir texto em array com base no ;  , devoluçao do primeiro elemento do array(estado)
	return r.text.strip().split(";")[0] 
	
	
	#Função para alternar o estado do alarme através do botão	
def switchEstado():
	estado=getEstado()
	if estado=='Desativado' or estado=='Desativado30':
		post2API('Ativo')
	elif estado=='Ativo' or estado=='Acionado':
		post2API('Desativado')
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
takeFoto = True
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
			#Para que não seja tirada uma foto em cada iteração, é preciso verificar
			#Quando o alarme estava ativo, e então foi desativado
			#Caso led verde estivesse acesa antes de efetuar qualquer alteração, é sinal de que o alarme
			#Estava ativo e acabou de ser desativado
			if(ledVerde.is_lit):
				capturar_e_enviar()
			ledAmarelo.on()
			ledVerde.off()
			ledVermelho.off()
			#Variavel verificadora para caso "Acionado"
			takeFoto = True
		elif estado=='Acionado':
			#Variavel verificadora para não serem tiradas infinitas fotos enquanto preso no acionado
			#A ideia é apenas tirar a foto no momento do alarme acionado
			if takeFoto:
				capturar_e_enviar()
			ledVermelho.on()
			ledAmarelo.off()
			ledVerde.off()
			takeFoto = False
		elif estado=='Ativo':
			ledVerde.on()
			ledVermelho.off()
			ledAmarelo.off()
			takeFoto = True
		elif estado=='Desativado30':
			if(ledVerde.is_lit):
				capturar_e_enviar()
			ledVerde.off()
			ledVermelho.off()
			ledAmarelo.blink()
			takeFoto = True
		sleep(2)
	#Mensagem para caso de interrupção do utilizador (^C)
	except KeyboardInterrupt:
		print('\n O script foi interrompido pelo Utilizador.')
		#Desligamento dos leds e liberação dos pinos
		ledVerde.off()
		ledVermelho.off()
		ledAmarelo.off()
		gpio.cleanup()
		break
	#Mensagem para outros casos
	except Exception as e:
		print('Erro inesperado:', e)
		#Desligamento dos leds e liberação dos pinos
		ledVerde.off()
		ledVermelho.off()
		ledAmarelo.off()
		gpio.cleanup()
		break
