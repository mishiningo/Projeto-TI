<!doctype html>
<?php
  session_start();
  
  
  $users = [
        "Leo" => [
            "password" => '$2y$12$pq1xvMVLijW3DInENog3x.f8WWHLdm/WsVDX/hVcufsMUvDHMsyIq', // 123 para todos os utilizadores
            "nivel"    => "Morador"
        ],
        "Maria" => [
            "password" => '$2y$12$pq1xvMVLijW3DInENog3x.f8WWHLdm/WsVDX/hVcufsMUvDHMsyIq',
            "nivel"    => "Visitante"
        ],
        "Tomas" => [
            "password" => '$2y$12$pq1xvMVLijW3DInENog3x.f8WWHLdm/WsVDX/hVcufsMUvDHMsyIq',
            "nivel"    => "Seguranca"
        ]
    ];
  
  
  
  if(isset($_POST['password']) and isset($_POST['username'])){
      if (password_verify ($_POST['password'], $users[$_POST['username']]['password'])){
          echo "Credenciais corretas!";
          header("refresh:0;url=dashboard.php");
          $_SESSION["username"]=$_POST['username'];
          $_SESSION["nivel"]=$users[$_POST['username']]['nivel'];
    }
    else{
        echo"Crendeciais inválidas!";
    }
  }   
?>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login em MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="estiloLogin.css"> -->
  </head>
  <body>
    <div class="container">
        <div class="row justify-content-center">
            <form class="LoginForm" method ="post">
                <a href="login.php">
                    <image src="imagens/logo.png">
                </a>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Utilizador</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu nome de utilizador" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Palavra-passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Sua palavra-passe" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>