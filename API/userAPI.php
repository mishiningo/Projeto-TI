<?php
    //Realiza a sessão no auth.php
    require_once '../auth.php';
    header('Content-Type: text/plain; charset=utf-8');
    //Verifica se é um administrador que o está a fazer
    if (!isset($_SESSION['username'], $_SESSION['nivel']) || $_SESSION['nivel'] != 'admin') {
        http_response_code(403);
        echo "Acesso negado";
        return;
    }

    $key = getenv('ALARM_KEY');
    define('ALARM_KEY', hex2bin(e2881a9eebe383fb671953b3fe22bf6bcc65e754b67f36623bfeab2525fbcc77));
    
    function cifrar(string $texto): string {
        // Vetor de inicio são 16 bytes aleatorios para randomizar a cifragem
        $iv = random_bytes(16);
        // Realiza a cifragem de texto + iv, em formato aes.., utilizando a chave de cifragem, devolvendo tudo em bytes puros(RAW)
        $cifrado = openssl_encrypt($texto, 'AES-256-CBC', ALARM_KEY, OPENSSL_RAW_DATA, $iv);
        // Guarda IV + dados cifrados, tudo em base64 para ficar em texto simples
        return base64_encode($iv . $cifrado);
    }

    function decifrar(string $texto): string|false {
        $raw = base64_decode($texto, strict: true);
        if ($raw === false || strlen($raw) < 17) return false;
        $iv      = substr($raw, 0, 16);
        $cifrado = substr($raw, 16);
        return openssl_decrypt($cifrado, 'AES-256-CBC', ALARM_KEY, OPENSSL_RAW_DATA, $iv);
    }

    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Verifica se foram passados todos os parametros
        if (!isset($_POST['username'], $_POST['password'], $_POST['nivel'])) {
                    http_response_code(400);
                    echo "Faltam parâmetros";
                    return;
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            // Verifica se não foram usado caracteres invalidos
            echo "Caracteres inválidos";
            http_response_code(400);
            return;
        }
        //Cria a hash e a armazena no devido ficheiro. Tambem cria um ficheiro para o nivel de aceeso
        $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        file_put_contents("files/utilizadores/hashs/" . $_POST['username'] . ".txt", $pass_hash);
        file_put_contents("files/utilizadores/niveis/" . $_POST['username'] . ".txt", cifrar($_POST['nivel']));
        header("refresh:0;url=../perfis.php");
    }else if ($_SERVER['REQUEST_METHOD'] == "GET"){
        // Função que devolve um array de todos os ficheiros com o seguinte caminho
        // * -> Linux style
        $ficheiros = glob("files/utilizadores/niveis/*.txt");
        //Devolve falso em caso de erro
        if ($ficheiros == false){
            echo "Houve um erro ao carregar os utilizadores";
            http_response_code(500);
        } 
        //Estrutura for each pra percorrer vetor $ficheiros
        //Ou seja, para cada elemento em ficheiros teremos uma var caminho para ser utilizada dentro do for
        foreach($ficheiros as $caminho){
        $nomeFicheiro = basename($caminho); //Basename exclui o caminho complet, devolvendo somente o nome do ficheiro
        //Ex: API/files/niveis/Leo.txt -> Leo.txt

        //Substr é uma função para criar uma sbstring com base nos parametros passados
        //Neste caso o $nomeFicheiro é a base, 0 é onde deve-se começar a string (caminho[0]), e o -strlen(".txt") é onde ela deve acabar
        //Ou seja, -4 iterações ( - ".txt") do fim, excluindo assim a extensão do ficheiro
        //Resultando na string username sem a extensão do ficheiro 
        $username = substr($nomeFicheiro, 0, -strlen(".txt"));
        // Necessário decifrar o níveç
        $nivel = decifrar(file_get_contents($caminho));
        echo $username . ";" . $nivel . PHP_EOL;
        } 
    }
        else{
        http_response_code(405);
        echo "Método não permitido";
    }
?>