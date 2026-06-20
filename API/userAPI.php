<?php
    header('Content-Type: text/html; charset=utf-8');
    if (!isset($_POST['username'],$_POST['password'])) {
                http_response_code(400);
                echo "Faltam parâmetros";
                return;
    }
    $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    file_put_contents("files/utilizadores/" . $_POST['username'] .".txt", pass_hash);
    file_put_contents("files/utilizadores/" . $_POST['username'] . "NVL" .".txt", pass_hash);
?>