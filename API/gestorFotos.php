<?php
header('Content-Type: text/html; charset=utf-8');
// Pedidos POST são para receção de fotos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imagem'])){
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], 'files/webcam/' . $_FILES['imagem']['name'])) {
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
    // Pedidos GET para "envio" de fotos 
} else if($_SERVER['REQUEST_METHOD'] == 'GET'){
    
        if(!isset($_GET['solicitante'])){
            http_response_code(400);
            echo "Faltam parâmetros";
            return;
        }

        $imagens = glob('files/webcam/*.jpg');
        if (empty($imagens)) {
        http_response_code(404);
        echo "Nenhuma imagem encontrada";
        exit;
    }
    //Usort compara elementos do array com base na função fn
    //Usort compara a com b de modo semelhante ao strcmpr no c
    // 0 se iguais, -1 se a for maior 1 se b for maior
    // Neste caso ainda usa-se o filemtime que devolve o tempo da ultima modificação
    // De cada ficheiro em unix epoch
    // fn é declarada de modo "implicito(?)" atravez da arrow  
    usort($imagens, fn($a, $b) => filemtime($b) - filemtime($a));
    if($_GET['solicitante'] == "dashboard"){
        // Primeira posição do array será a imagem mais recente, logo a necessária pra dashboard
        echo basename($imagens[0]);
    }else if ($_GET['solicitante'] == "historico"){
        // historico requer as ultimas 10 imagens mais recentes
        // $limite é para o caso de não haverem pelo menos 10 imgs (sofri pra descobrir isso)
        $limite = min(10, count($imagens));
        for ($i = 0; $i < $limite; $i++){
            echo basename($imagens[$i]) . "\n";
        }
    }

}
?>