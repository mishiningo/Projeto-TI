<?php
    $env = parse_ini_file('.env');
    define('ALARM_KEY', hex2bin(trim($env['ALARM_KEY'])));

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

    echo cifrar("Desativado");
?>