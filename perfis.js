async function pedido() {
        try {
            const resposta = await fetch(`API/userAPI.php`);

            // Verificação da receção da resposta
            if (!resposta.ok) throw new Error("Erro na resposta do servidor");

            // Conversão da resposta para texto e depois vetor
            const respostaTexto = await resposta.text();
            const respostaVetor = respostaTexto.split("\n");
            const tabelaPerfis = document.getElementById("tabela-perfis");
            tabelaPerfis.innerHTML = "";
            respostaVetor.forEach(usuario =>{
                if (!usuario.trim()) return;
                // Mesma lógica do histórico
                const partes = usuario.split(";").map(elem => elem.trim());
                const colunas = partes.map(col => `<td>${col}</td>`).join("");
                tabelaPerfis.innerHTML += `<tr>${colunas}</tr>`;                
            })
            } catch (erro) { //Caso de erro
            console.error("Falha ao atualizar:", erro);
        }
}

pedido();
setInterval(pedido,5000);