<?php
    $env = parse_ini_file(__DIR__ . "/../.env");
    //_DIR_ devolve o diretorio do ficheiro, nao do processo
    
    if ($env === false || empty($env['ALARM_KEY'])) {
        // Cenário de erro do .env
        die('Erro: configuração inválida.');
        }
    
    define('ALARM_KEY', hex2bin(trim($env['ALARM_KEY'])));
        
    function cifrar($data) {
        // Esta cifragem necessita de um vetor de inicialização (IV) único para cada operação de cifragem,
        // O IV são 16 bytes gerados aleatoriamente e concatenados com o texto cifrado para que possa ser usado na decifragem
        $iv = random_bytes(16);
        $textoCifrado = openssl_encrypt($data, 'aes-256-cbc', ALARM_KEY, OPENSSL_RAW_DATA, $iv);
        // O resultado é devolvido em bytes puros (raw), portanto passamos a base64 para armazenar em .txt
        if ($textoCifrado === false) {
            // Verifica-se se a cifragem falhou e lança uma exceção com a mensagem de erro do OpenSSL,
            // para ser utilizado em um bloco try-catch na função que chama a cifragem
            throw new RuntimeException('Erro na cifragem: ' . openssl_error_string());
        }
        return base64_encode($iv . $textoCifrado);
    }

    function decifrar($data) {
        // Decodifica o texto cifrado de base64 para bytes puros
        $data = base64_decode($data);
        // Extrai o IV (primeiros 16 bytes) e o texto cifrado (restante) com a função substr para percorrer a string
        $iv = substr($data, 0, 16);
        $textoCifrado = substr($data, 16);
        // Decifra o texto usando a mesma chave e IV
        $textoDecifrado = openssl_decrypt($textoCifrado, 'aes-256-cbc', ALARM_KEY, OPENSSL_RAW_DATA, $iv);
        if ($textoDecifrado === false) {
            // Verifica-se se a decifragem falhou e lança uma exceção com a mensagem de erro do OpenSSL
            throw new RuntimeException('Erro na decifragem: ' . openssl_error_string());
        }
        return $textoDecifrado;
    }
?>