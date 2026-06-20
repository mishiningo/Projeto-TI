<?php
  require_once 'auth.php';
  
  if(isset($_POST['password']) and isset($_POST['username'])){
      $pass_hash = file_get_contents("API/files/utilizadores/hashs/" . $_POST['username'] .".txt");
      if (password_verify ($_POST['password'], $pass_hash)){
          echo "Credenciais corretas!";
          $_SESSION["username"]=$_POST['username'];
          $_SESSION["nivel"]=file_get_contents("API/files/utilizadores/niveis/" . $_POST['username'] . "NVL" .".txt");
          header("refresh:0;url=dashboard.php");
          }
          else{
              $erro = "<div class=\"erro\"><h4>Crendeciais inválidas!</h4></div>";
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
    <link rel="stylesheet" href="estiloLogin.css">
  </head>
  <body>
    <div class="container-fluid d-flex text-center align-items-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <form class="LoginForm myHomeStyle" method ="post">
                <a href="login.php">
                    <img src="imagens/logo.png" class ="rounded float-center logo">
                </a>
                <?php if (isset($erro)) {echo $erro;} ?>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Utilizador</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu nome de utilizador" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Palavra-passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Sua palavra-passe" name="password" required>
                </div>
                <button type="submit" class="btn btn-myhome">Enviar</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>