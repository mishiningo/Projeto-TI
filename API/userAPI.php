<?php
    //Realiza a sessão no auth.php
    require_once '../auth.php';
    require_once 'cifragem.php';
    header('Content-Type: text/html; charset=utf-8');
    //Verifica se é um administrador que o está a fazer
    if (!isset($_SESSION['username'], $_SESSION['nivel']) || $_SESSION['nivel'] != 'admin') {
        http_response_code(403);
        echo "Acesso negado";
        return;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $ficheiros = glob("files/utilizadores/niveis/*.txt");
        if ($ficheiros === false) {
            http_response_code(500);
            echo "Erro ao carregar utilizadores";
            return;
        }

        foreach ($ficheiros as $caminho) {
            // basename devolve o nome do ficheiro + extensao
            // substr gera uma sbtr de basename(caminho), começando no 0 
            // e terminando em -strlen(".txt") para remover a extensão
            $username = substr(basename($caminho), 0, -strlen(".txt"));
            try {
                $nivel = decifrar(file_get_contents($caminho));
                // Devido a facilidade de ter o forEach na API, e esta ser somente
                // chamada pelo perfis.php, a API devolve a string ja formatada para o js somente atualizar o html
                // Periodicamente
                echo "<tr>
                        <td>" . htmlspecialchars($username) . "</td>
                        <td>" . htmlspecialchars($nivel) . "</td>
                      </tr>";
            } catch (RuntimeException $e) {
                // Caso a decifragem falhe, devolve-se o username e uma mensagem de erro no nivel
                echo "<tr>
                        <td>" . htmlspecialchars($username) . "</td>
                        <td>Erro ao decifrar</td> 
                      </tr>";
            }
        }
        return;
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //Verifica se foram passados todos os parametros
        if (!isset($_POST['username'], $_POST['password'], $_POST['nivel'])) {
                    http_response_code(400);
                    echo "Faltam parâmetros";
                    return;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            // Verifica se o utilizador possui apenas a-z, 0-9 ou _
            // E pelo menos um char (+). Qualquer coisa fora disso sai do padrão entregue e é devolvido 0
            echo "Caracteres inválidos";
            http_response_code(400);
            return;
        }

        try{
            //Cria a hash e a armazena no devido ficheiro. Tambem cria um ficheiro para o nivel de aceeso
            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            file_put_contents("files/utilizadores/hashs/" . $_POST['username'] . ".txt", $pass_hash);
            // Niveis são cifrados
            file_put_contents("files/utilizadores/niveis/" . $_POST['username'] . ".txt", cifrar($_POST['nivel']));
            header("refresh:0;url=../perfis.php");
        } catch (Exception $e) {
            echo "Erro ao criar utilizador: " . $e->getMessage();
            http_response_code(500);
        }
    }else {
        http_response_code(405);
        echo "Método não permitido";
    }
?>