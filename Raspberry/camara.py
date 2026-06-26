import cv2
import time
import requests
import datetime


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
				r = requests.post('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/API/upload.php', files=ficheiros)
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

capturar_e_enviar()
