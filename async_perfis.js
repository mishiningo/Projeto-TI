async function carregarUtilizadores() {
        const tabelaUtilizadores = document.getElementById('tabela-utilizadores');
        try {
            const resposta = await fetch('API/userAPI.php');
            if (!resposta.ok) {
                // Cenario de erro no get, devolve uma linha com a mensagem de erro
                tabelaUtilizadores.innerHTML = '<tr><td colspan="2">Erro ao carregar utilizadores</td></tr>';
                return;
            }
            // Caso de sucesso é só passar o texto devolvido pela API para o innerHTML do tbody
            tabelaUtilizadores.innerHTML = await resposta.text();
        } catch (error) {
            tabelaUtilizadores.innerHTML = '<tr><td colspan="2">Erro de comunicação com o servidor</td></tr>';
        }
    }

carregarUtilizadores();
setInterval(carregarUtilizadores, 30000); // Atualiza a cada 30 segundos