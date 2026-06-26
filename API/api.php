<?php
    header('Content-Type: text/html; charset=utf-8');
    //Declaração de função utilizada repetidas vezes
    function processarAPI(array $dados): void {
        //Verificação previa se $dados foram enviados corretamente
        if (!isset($dados['estado'], $dados['hora'], $dados['origem'])) {
            http_response_code(400);
            echo "Faltam parâmetros";
            exit;
            }

        $estado = $dados['estado'];
        $hora   = $dados['hora'];
        // Origem só tem função para os logs
        $origem = $dados['origem'];
        

        //file_put_contents devolve false em caso de falha
        // Cifra cada valor antes de escrever .txt
        $ret_escrita  = file_put_contents("files/alarme/estado.txt", cifrar($estado))  !== false;
        $ret_escrita  = $ret_escrita && file_put_contents("files/alarme/hora.txt",  cifrar($hora))   !== false;

        $ret_escrita = $ret_escrita && appendLog($hora . ";" . $estado . ";" . $origem);

        if (!$ret_escrita) {
            http_response_code(500);
            echo "Erro ao escrever nos ficheiros da API";
            exit;
        }
        http_response_code(200);
        echo "OK";          
}

function enviarAPI($get): void{
    if (!isset($get['origem'])) {
            http_response_code(400);
            echo "Faltam parâmetros";
            exit;
            }
    $origem = $get['origem'];

    if($origem == "Arduino" || $origem == "Dashboard" || $origem == "Raspberry"){

        $estado = file_get_contents("files/$nome/estado.txt");
        $hora   = file_get_contents("files/$nome/hora.txt");
        if ($estado === false || $hora === false) {
            http_response_code(500);
            echo "Erro ao ler os ficheiros da API";
            return;
            }
            http_response_code(200);
            echo "$estado; $hora";
    }else if($origem == "Historico"){
        $log = file_get_contents("files/alarme/log.txt");
        if ($log === false) {
            http_response_code(500);
            echo "Erro ao ler os logs da API";
            exit;
            }
            http_response_code(200);
            echo "$log";
        }
} 
    
    //Chama-se a função permitindo apenas os métodos GET para enviar ou POST para receção
    switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        enviarAPI($_GET);
        break;
    case 'POST':
        processarAPI($_POST);
        break;
    default:
        http_response_code(405);
        echo "Método não permitido";
    }

    if(isset($_POST['origem']) && $_POST['origem'] == "Dashboard"){
        header("Location: ../dashboard.php");
    }
?>