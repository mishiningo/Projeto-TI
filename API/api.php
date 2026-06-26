<?php
    header('Content-Type: text/html; charset=utf-8');
    //Declaracão da chave de cifragem (criada em openssl)
    //Parse_ini.. cria um array associativo ([X] => Y)
    $env = parse_ini_file(__DIR__ . '/../.env');
    //Trim limpa espaços em branco
    //hex2bin converte a chave guardada em hexadecimal para binario
    define('ALARM_KEY', hex2bin(trim($env['ALARM_KEY'])));

    //Cifragem realizada em AES-256-CBC:
    //Função de cifragem
    function cifrar(string $texto): string|false {
    //iv é um vetor de inicialização de 16 bytes aleatórios adicionando mais segurança
    $iv = random_bytes(16);
    //Função de encriptação da biblioteca openssl,
    // Necessita do conteudo, tipo de cifragem, chave de cifragem, o modo de retorno do conteudo
    // cifrado, neste caso RAW_DATA devolve os bytes puramente falando (0 já agregaria base64),
    //e o vetor de inicialização
    $cifrado = openssl_encrypt($texto, 'AES-256-CBC', ALARM_KEY, OPENSSL_RAW_DATA, $iv);
    if($cifrado === false){
        // Devolve false em erro
        echo "Erro na criptografia: " . openssl_error_string();
        return false;
    }
    // Guarda IV + dados cifrados, tudo em base64 (64 caracteres) para ficar em texto simples (txt)
    return base64_encode($iv . $cifrado);
    
    }
    //Função de decifragem que devolve uma string ou falso
    function decifrar(string $texto): string|false {
    //Voltamos de base 64 para binário, o strict é para garantir que todos os caracteres são convertidos
    //Com ele a true caso a decodificação falhe retorna false
    $raw = base64_decode($texto, strict: true);
    if ($raw === false || strlen($raw) < 17){
        //caso o decode retorna falso falha, mas há uma proteção extra
        //como o iv é de 16 octetos, a cifragem precisa ter pelo menos 17 bytes,
        //caso o contrário não pertence à este algoritmo
        return false;
    }
    //Reassocia-se o iv (primeiros 16 bytes do conteudo)
    $iv      = substr($raw, 0, 16);
    //Mesmo processo realizado acima (neste caso todo os bytes a partir do 16º)
    $cifrado = substr($raw, 16);
    // Sintaxe identica à de cifragem, retorna false em caso de falha e o texto decrifado em sucesso
    // RAW aqui indica que os dados estão sendo passado em bytes diretamente, e nao em b64
    return openssl_decrypt($cifrado, 'AES-256-CBC', ALARM_KEY, OPENSSL_RAW_DATA, $iv);
}

function appendLog(string $novaLinha): bool {
    $caminho = "files/alarme/log.txt";  
    $log_old = '';  
    // Lê o log existente 
    if (file_exists($caminho)) {
        // Decifra-o e adiciona-se a nova linha
         $log_old = decifrar(file_get_contents($caminho)) . PHP_EOL . PHP_EOL;
        }
        // Retorna-se um valor booleano para manter com a lógica do processar API
        // log_old declarado antes como uma string vazia caso if nao seja acionado
        $log_new = $log_old . $novaLinha . PHP_EOL . PHP_EOL;
        return file_put_contents($caminho, cifrar($log_new)) !== false;
        }

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
        $estado = decifrar(file_get_contents("files/alarme/estado.txt"));
        $hora   = decifrar(file_get_contents("files/alarme/hora.txt"));
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
            echo decifrar($log);
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