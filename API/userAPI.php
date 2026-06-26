<?php
    //Realiza a sessão no auth.php
    require_once '../auth.php';
    header('Content-Type: text/html; charset=utf-8');
    //Verifica se é um administrador que o está a fazer
    if (!isset($_SESSION['username'], $_SESSION['nivel']) || $_SESSION['nivel'] != 'admin') {
        http_response_code(403);
        echo "Acesso negado";
        return;
    }
    //Verifica se foram passados todos os parametros
    if (!isset($_POST['username'], $_POST['password'], $_POST['nivel'])) {
                http_response_code(400);
                echo "Faltam parâmetros";
                return;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
        echo "Caracteres inválidos";
        http_response_code(400);
        return;
    }


    //Cria a hash e a armazena no devido ficheiro. Tambem cria um ficheiro para o nivel de aceeso
    $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    file_put_contents("files/utilizadores/hashs/" . $_POST['username'] . ".txt", $pass_hash);
    file_put_contents("files/utilizadores/niveis/" . $_POST['username'] . ".txt", $_POST['nivel']);
    header("refresh:0;url=../perfis.php");
?>