// Declaração de constantes para mapear os estados do alarme para o gráfico
const ESTADO_VALOR = {
    "Ativo":            2,
    "Acionado":           3,
    "Desativado":         0,
    "Desativado por 30s": 1,
};

// Função para determinar a cor do ponto no gráfico com base no estado do alarme
function corDoEstado(estado) {
    if (estado === "Ativo")  return "#2ecc71";  // verde
    if (estado === "Acionado") return "#e74c3c";  // vermelho
    return "#f1c40f";                              // amarelo (Desativado / Desativado por 30s)
}

// Configuração do gráfico usando Chart.js
const ctx = document.getElementById("grafico-alarme").getContext("2d");

// Criação do gráfico de linha, ainda sem dados, que serão preenchidos dinamicamente
// Apenas configurações de estilo e comportamento do gráfico são definidas aqui
const grafico = new Chart(ctx, {
    type: "line",
    data: {
        labels: [],
        datasets: [{
            label: "Estado do Alarme",
            data: [],
            borderWidth: 0,        // remove o contorno dos pontos
            pointRadius: 6,        // tamanho dos pontos
            pointBackgroundColor: [],
            showLine: false,       // remove a linha entre pontos
            fill: false,       
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                min: -1.2,
                max: 4.2,
                ticks: {
                    stepSize: 1,
                    callback: value => {
                        if (value === 2) return "Ativo"; // Mapeamento dos valores para os rótulos do eixo Y
                        if (value === 3) return "Acionado";
                        if (value === 0) return "Desativado";
                        if (value === 1) return "Desativado por 30s";
                        return "";
                    }
                },
                grid: { color: "rgba(0,0,0,0.05)" }
            },
            x: {
                grid: { color: "rgba(0,0,0,0.05)" },
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const mapa = { 2: "Ativo", 3: "Acionado", 0: "Desativado", 1: "Desativado por 30s" }; // Mapeamento dos valores para os rótulos do tooltip
                        return ` Estado: ${mapa[ctx.raw] ?? "Desconhecido"}`;
                    }
                }
            }
        }
    }
});

//Função javascript para buscar dados de 5 em 5 segundos sem necessitar de refresh
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

            //Declaração dos elementos do gráfico
            const labels  = [];
            const valores = [];
            const cores   = [];

            // Preenche conteudo do html
           const elementoHistorico = document.getElementById("tabela-historico");
            elementoHistorico.innerHTML = ""; // Limpa antes de preencher 
            respostaVetor.forEach(item => {
                // Casos encontrados como Desativados30 passam a Desativado por 30s, mas apenas as matches!!
                const itemFormatado = item.replace("Desativado30", "Desativado por 30s");
                // Separação do vetor usando o ; e mapeia as colunas para criar as células da tabela, depois junta tudo em uma única string
                const partes = itemFormatado.split(";").map(col => col.trim());
                const colunas = partes.map(col => `<td>${col}</td>`).join("");
                elementoHistorico.innerHTML += `<tr>${colunas}</tr>`;
                //Associa valores às respetivas variáveis
                const hora   = partes[0];
                const estado = partes[1];
                //Push dos valores para os vetores do gráfico
                labels.push(hora);
                valores.push(ESTADO_VALOR[estado]);
                cores.push(corDoEstado(estado));
        });
        //Atualiza o gráfico com os novos dados
        grafico.data.labels                            = labels;
        grafico.data.datasets[0].data                 = valores;
        grafico.data.datasets[0].pointBackgroundColor = cores;
        grafico.update();
        } catch (erro) { //Caso de erro
            console.error("Falha ao atualizar:", erro);
        }
    }
    pedido();
    //Atualizações a cada 5 segundos
    setInterval(pedido, 5000);
    