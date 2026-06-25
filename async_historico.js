//Função javascript para buscar dados de 5 em 5 tempo sem necessitar de refresh
async function pedido() {
        try {
            const resposta = await fetch(`API/api.php?nome=alarme&origem=Historico`);

            // Verificação da receção da resposta
            if (!resposta.ok) throw new Error("Erro na resposta do servidor");

            // Conversão da resposta para texto e depois vetor
            const respostaTexto = await resposta.text();
            //Separação do vetor usando a quebra de linha (tanto windows quanto linux) como referencia.
            //Filtra linhas vazias e pega apenas as últimas 10 entradas
            const respostaVetor = respostaTexto.split(/\r?\n/).filter(linha => linha.trim() !== "").slice(-10);

            // Preenche conteudo do html
           const elementoHistorico = document.getElementById("tabela-historico");
            elementoHistorico.innerHTML = ""; // Limpa antes de preencher 
            respostaVetor.forEach(item => {
                // Casos encontrados como Desativados30 passam a Desativado por 30s, mas apenas as matches!!
                const itemFormatado = item.replace("Desativado30", "Desativado por 30s");
                // Separação do vetor usando o ; e mapeia as colunas para criar as células da tabela, depois junta tudo em uma única string
                const colunas = itemFormatado.split(";").map(col => `<td>${col.trim()}</td>`).join("");
                elementoHistorico.innerHTML += `<tr>${colunas}</tr>`;
        });
            
        } catch (erro) { //Caso de erro
            console.error("Falha ao atualizar:", erro);
        }
    }
    pedido();
    //Atualizações a cada 5 segundos
    setInterval(pedido, 5000);
    