//Função javascript para buscar dados de 5 em 5 tempo sem necessitar de refresh
    async function pedido() {
        try {
            const resposta = await fetch(`API/api.php?origem=Dashboard`);

            // Verificação da receção da resposta
            if (!resposta.ok) throw new Error("Erro na resposta do servidor");

            // Conversão da resposta para texto e depois vetor
            const respostaTexto = await resposta.text();
            //Separação do vetor usando o ; como referencia
            const respostaVetor = respostaTexto.split("; ");
            //Função trim limpa espaços em brancos ou \n dos ficheiros
            const estado = respostaVetor[0].trim();
            const hora   = respostaVetor[1].trim();

            // Passa conteudo da id label-estado
            if(estado === "Desativado30"){
                // Pequeno ajuste do nome
                document.getElementById("label-estado").textContent = "Desativado por 30 segundos";
            } else{
                document.getElementById("label-estado").textContent = estado;
            }

            let btns;
            let src;
            //Analisa caso a caso para averiguar quais imagens atualizar
            switch (estado){
                case "Desativado30": 
                case "Desativado":
                    src = "imagens/AlarmeOFF.png";
                    btns = `<tr>
									<td>
									    <button type="submit" name="estado" value="Ativo" class="btn-imagem">
										    <img src="imagens/on.png" class="imgAlarme" title="Clique para ativar">
										</button>
									</td>
								</tr>`;
                    break;
                case "Ativo":
                    src = "imagens/AlarmeON.png";
                        // Todos os utilizadores veêm o botão de 30s
                        btns = `<tr>
									<td>
										<button type="submit" name="estado" value="Desativado30" class="btn-imagem">
											<img src="imagens/off30.png" class="imgAlarme" title="Clique para desativar por 30s">
										</button>
									    <hr>
									</td>
								</tr> `;
                        if (nivelUtilizador !== "visitante"){
                            //nivelUtilizador tem de ser passada dentro da dashboard
                            // Admin e utilizadores normais veem os dois botões
                            btns += `<tr>
									    <td>
                                            <button type="submit" name="estado" value="Desativado" class="btn-imagem">
                                                <img src="imagens/off.png" class="imgAlarme" title="Clique para desativar">
                                            </button>
                                        </td>
                                    </tr>`;
                        }
                    break;
                case "Acionado":
                    src = "imagens/AlarmeHIT.png";
                    btns = `<tr>
									<td>
										<button type="submit" name="estado" value="Desativado30" class="btn-imagem">
											<img src="imagens/off30.png" class="imgAlarme" title="Clique para desativar por 30s">
										</button>
									</td>
								</tr>`;
                    if (nivelUtilizador !== "visitante"){
                        //nivelUtilizador tem de ser passada dentro da dashboard
                        // Admin e utilizadores normais veem os dois botões
                        btns += `<tr>
								    <td>
                                        <button type="submit" name="estado" value="Desativado" class="btn-imagem">
                                            <img src="imagens/off.png" class="imgAlarme" title="Clique para desativar">
                                        </button>
                                    </td>
                                </tr>`;
                    }
                    break; 
            }        
            
            // Preenche conteudo do html
            document.getElementById("imagem-alarme").innerHTML = `<img src="${src}" class="imgAlarme mt-5">`;
            document.getElementById("controlo-alarme").innerHTML = btns;
            document.getElementById("hora-alarme").textContent = hora;

        } catch (erro) { //Caso de erro
            console.error("Falha ao atualizar:", erro);
        }
    }
    // Função para carregar a última imagem
    async function carregarUltimaFoto() {
    try {
        const r = await fetch('API/gestorFotos.php?solicitante=dashboard');
        
        if (!r.ok) {
            console.error('Erro ao buscar foto:', r.status);
            return;
        }

        const nomeImagem = await r.text();

        document.getElementById('corpo-foto').innerHTML = 
            `<img src="API/files/webcam/${nomeImagem.trim()}" class="img-fluid rounded" alt="Última fotografia">`;
        
        document.getElementById('rodape-foto').innerHTML = 
            `Imagem: <b>${nomeImagem.trim()}</b>`;

    } catch (e) {
        console.error('Erro na comunicação:', e);
    }
}

    pedido();
    //Atualizações a cada 5 segundos
    setInterval(pedido, 5000);
    carregarUltimaFoto();
    // Ficheiros maiores = mais intervalado
    setInterval(carregarUltimaFoto, 50000);
    