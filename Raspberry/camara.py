import cv2
import time
import requests
import datetime


def capturar_e_enviar():
		nomeData = str(datetime.datetime.now())
		nomeFicheiro = nomeData.replace(" ", "").replace("-", "").replace(":","").replace(".","")
		
		print("A ligar  DroidCam e a aguardar 3 segundos para focar...")
		cap = cv2.VideoCapture("http://10.20.228.179:4747/video")
		 
		# 1. Espera 3 segundos com a camara ligada para ela ter tempo de focar
		time.sleep(3)
		
		# 2. Captura a imagem final
		ret, frame = cap.read()
		cap.release() # Liberta a camara imediatamente

		if ret:
			print("Imagem capturada com sucesso! A preparar envio...")
			
			# 4. Codifica o frame do OpenCV (.png/.jpg) diretamente na memoria para JPEG
			sucesso_conversao, img_bytes = cv2.imencode('.jpg', frame)
			
			if not sucesso_conversao:
				print("Erro ao converter o frame para JPEG.")
				return False
				
			# 5. Prepara o dicionario de ficheiros com o nome do campo esperado pelo PHP ('imagem')
			ficheiros = {
				'imagem': (nomeFicheiro, img_bytes.tobytes(), 'image/jpeg')
			}
			
			try:
				# Envia o ficheiro para o teu upload.php via POST
				resposta = requests.post('https://iot.dei.estg.ipleiria.pt/ti/ti061/ProjetoTI/upload.php', files=ficheiros)
				
				
				if resposta.status_code == 200 and "Upload OK" in resposta.text:
					print("[+] Sucesso: Imagem guardada no servidor do site!")
					return True
				else:
					print(f"[-] Erro no Servidor Web: Codigo {resposta.status_code}. Resposta: {resposta.text}")
					return False
					
			except Exception as e:
				print("Erro ao tentar comunicar com o servidor de upload:", e)
				return False
		else:
			print("Erro: Nao foi possivel ler o frame da DroidCam.")
			return False

capturar_e_enviar()
