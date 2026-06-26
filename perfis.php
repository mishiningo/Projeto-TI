<?php 
     require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfis de MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- Imports da fonte (warning css) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  </head>
  <body>
    <?php
    if (!isset($_SESSION['username'], $_SESSION['nivel']) || $_SESSION['nivel'] !== 'admin') {
        echo "<div class=\"erro\"><h4>Acesso negado!</h4></div>";
        header("refresh:5;url=login.php");
        die();
    }
    ?>
   <nav class="navbar navbar-expand-sm turquesa px-4">
    <span class="navbar-brand text-white fw-bold">My Home Stats</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item active">
                <!-- Sem necessidade de verificação, pois a mesma já é feita ao aceder a pg -->
                <a href="perfis.php" class="nav-link active">Perfis</a>
            </li>
            <li class="nav-item">
                <!-- Não é necessário verificação, pois a mesma já é feita ao aceder a pg -->
                <a href="historico.php" class="nav-link">Histórico</a>
            </li>
        </ul>
        <a href="logout.php" class="btn btn-logout">
            Logout
        </a>
    </div>
    </nav>
      <div class="container-sm">
        <div class ="myHomeStyle d-flex justify-content-center mt-3">
            <div class="welcome rounded-border pt-2 text-center">
                <h1>Gestor de Perfis</h1>
            </div>
        </div>
        <br>
        <br>
        <div class="row justify-content-evenly">
            <div class="col-sm-6 mb-5">
                <div class="card text-center turquesa">
                    <div class = "card-header">
                        <b>Lista de perfis</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped-columns align-middle gelo rounded-table">
                            <thead>
                                <tr>
                                    <th>Perfis</th>
                                    <th>Nível</th>
                                </tr>
                            </thead>
                            <tbody id="tabela-utilizadores">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card text-center turquesa">
                    <div class="card-header">
                        <b>Criar novo usuário ou alterar passe de usuário existente</b> 
                    </div>
                    <div class="card-body">
                        <form method="post" action="API/userAPI.php">
                            <div class="mb-3 gelo rounded-border">
                                <label for="inputUsername" class="form-label">Utilizador</label>
                                <input type="text" class="inputs form-control py-2" id="inputUsername" aria-describedby="emailHelp" placeholder="Seu nome de utilizador" name="username" required>
                            </div>
                            <div class="mb-3 gelo rounded-border">
                                <label for="inputPassword" class="form-label">Palavra-passe</label>
                                <input type="password" class="inputs form-control py-2" id="inputPassword" placeholder="Sua palavra-passe" name="password" required>
                            </div>
                            <div class="gelo rounded-border">
                                <label for="selectNivel" class="form-label">Nivel de acesso</label>
                                <br>
                                <div class="inputs py-2">
                                    <select id="selectNivel" name="nivel">
                                        <option value="visitante" selected>Visitante</option>
                                        <option value="morador">Morador</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                            <div class="container-button gelo rounded-border py-1">
                            <button type="submit" class="btn btn-myhome my-1">Enviar</button> 
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="async_perfis.js"></script>
</body>
</html>