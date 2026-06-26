<?php
  require_once 'auth.php';
  
  if(isset($_POST['password']) and isset($_POST['username'])){
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            $erro = "<div class=\"erro\"><h4>Credenciais inválidas!</h4></div>";
            // Pequena proteção para o utilizador não utilizar caracteres especiais
            // Uma vez que buscam-se ficheiros com estes caracteres
            // Neste caso preg_match verifica se o utilizador possui apenas a-z, 0-9 ou _
            // E pelo menos um char (+). Qualquer coisa fora disso sai do padrão entregue e é devolvido 0
        }else {
            if(!file_exists("API/files/utilizadores/hashs/" . $_POST['username'] . ".txt")){
                // Caso do ficheiro não existir
                $erro = "<div class=\"erro\"><h4>Credenciais inválidas!</h4></div>";
            }else{
                $pass_hash = file_get_contents("API/files/utilizadores/hashs/" . $_POST['username'] .".txt");
                if (password_verify ($_POST['password'], $pass_hash)){
                        echo "Credenciais corretas!";
                        $_SESSION["username"]=$_POST['username'];
                        $_SESSION["nivel"]=file_get_contents("API/files/utilizadores/niveis/" . $_POST['username'] . ".txt");
                        header("refresh:0;url=dashboard.php");
                    }else{
                            $erro = "<div class=\"erro\"><h4>Credenciais inválidas!</h4></div>";
                            }
            }
        }
  }   
?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login em MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- Imports da fonte (warning css) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="container-fluid d-flex text-center align-items-center min-vh-100">
        <div class="row w-100 justify-content-center">
                <form class="LoginForm myHomeStyle" method ="post">
                    <a href="login.php">
                        <img src="imagens/logo.png" class ="rounded float-center logo" alt="My Home logo">
                    </a>
                    <?php if (isset($erro)) {echo $erro;} ?>
                    <div class="mb-3">
                        <label for="inputUsername" class="form-label">Utilizador</label>
                        <input type="text" class="form-control" id="inputUsername" aria-describedby="emailHelp" placeholder="Seu nome de utilizador" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Palavra-passe</label>
                        <input type="password" class="form-control" id="inputPassword" placeholder="Sua palavra-passe" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-myhome">Enviar</button>
                </form>  
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>