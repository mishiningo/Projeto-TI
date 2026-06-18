<?php
    header('Content-Type: text/html; charset=utf-8');
    //Declaração de função utilizada repetidas vezes
    function processarAPI(array $dados): void {
        //Verificação previa se $dados foram enviados
        if (!isset($dados['nome'], $dados['estado'], $dados['hora'])) {
            http_response_code(400);
            echo "Faltam parâmetros";
            return;
        }
        $nome = $dados['nome'];
        $estado = $dados['estado'];
        $hora   = $dados['hora'];

        //file_put_contents devolve false em caso de falha
        $ret_escrita = file_put_contents("files/$nome/estado.txt", $estado) !== false;
        if (!$ret_escrita) {
            http_response_code(500);
            echo "Erro ao escrever nos ficheiros da API";
            return;
        }
        $ret_escrita = file_put_contents("files/$nome/nome.txt", $nome) !== false;
        if (!$ret_escrita) {
            http_response_code(500);
            echo "Erro ao escrever nos ficheiros da API";
            return;
        }
        $ret_escrita = file_put_contents("files/$nome/hora.txt", $hora) !== false;
        if (!$ret_escrita) {
            http_response_code(500);
            echo "Erro ao escrever nos ficheiros da API";
            return;
        }
        $ret_escrita = file_put_contents("files/$nome/log.txt", "$hora;$estado" . PHP_EOL . PHP_EOL, FILE_APPEND) !== false;
        if (!$ret_escrita) {
            http_response_code(500);
            echo "Erro ao escrever nos ficheiros da API";
            return;
        }else {
            http_response_code(200);
            echo "OK";
        }
    }
    
    //Chama-se a função permitindo apenas os métodos GET ou POST
    switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        processarAPI($_GET);
        break;
    case 'POST':
        processarAPI($_POST);
        break;
    default:
        http_response_code(405);
        echo "Método não permitido";
    }
?>