<?php

header('Content-Type: text/html; charset=utf-8');
echo $_SERVER['REQUEST_METHOD'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imagem'])){
        print_r($_FILES['imagem']);
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], 'webcam/' . $_FILES['imagem']['name'] . '.jpg')) {
            echo ("Imagem recebida e salva com sucesso");
            http_response_code(200);
        }
        else{
            echo ("Erro ao salvar a imagem");
            http_response_code(500);
        }
    }

    else{
        echo ("Erro - imagem não recebida");
        http_response_code(400);
    }
}
else{
    echo ("Erro - método de requisição não permitido");
    http_response_code(405);
}
?>