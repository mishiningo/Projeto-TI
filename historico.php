<!DOCTYPE html>
<html lang="pt">
<?php 
	require_once 'auth.php';
	if(!isset($_SESSION['username'], $_SESSION['nivel']) || $_SESSION['nivel'] == 'visitante'){
    	header("refresh:5;url=login.php");
    	die("Acesso Restrito");
  }
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historico MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- Imports da fonte (warning css) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <!-- Bilblioteca de gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
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
			<?php
				if($_SESSION['nivel'] == "admin"){
					echo "<li class=\"nav-item\">
						<a href=\"perfis.php\" class=\"nav-link\">Perfis</a>
					</li>";
				} 
			?>
            <li class="nav-item active">
                <a href="historico.php" class="nav-link active">Histórico</a>
            </li>
        </ul>
        <a href="logout.php" class="btn btn-logout">
            Logout
        </a>
    </div>
    </nav>
	<div class="container-sm turquesa-border my-4">
        <br>
        <div class = "row mb-4">
            <div class="col-sm-12 mb-4">
                <div class ="myHomeStyle d-flex justify-content-center">
                    <div class="welcome rounded-border pt-2 text-center">
                        <h1>Histórico</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-sm-12">
                <h2>Estado do Alarme</h2>
                <hr class="turquesa-border">
                <div style="position: relative; height: 220px;">
                    <!-- Secção para gráfico -->
                    <canvas id="grafico-alarme"></canvas>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table rounded-table">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Origem</th>
                        </tr>
                    </thead>
                    <tbody id="tabela-historico">
                        <!-- As linhas da tabela serão preenchidas dinamicamente pelo JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container-sm turquesa-border py-4 mb-3">
        <h2>
            Últimas entradas de imagens:
        </h2>
        <hr class="turquesa-border">
        <div class ="row" id="historico-fotos">
        </div>
    </div>
    <!-- Modal de imagem -->
    <!-- Trata-se de camadas de div, onde uma é a animação, outra a cor de fundo, borda, uma centra a imagem, a outra recebe-a efetivamente, botão para sair e etc -->
    
    <!-- Animações e  container da img-->
    <div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
        <!-- Centralização -->
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Controno/Borda -->
            <div class="modal-content turquesa border-0">
                <!-- Cabeçalho (parte junta ao titulo) -->
                <div class="modal-header border-0">
                    <!-- Titulo do moldal (alterado dps em js pelo boostrap) -->
                    <!-- Trooca em relação ao padrão por conta dos validadores -->
                    <span class="modal-title h5 text-white" id="modalFotoCaption">Carregando...</span>
                    <!-- Botão de fechamento -->
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body e destino da imagem vai -->
                <div class="modal-body text-center p-2">
                   <!--Atribuição de imagem generica (quase) não visivel para não disparar validadores  -->
                <img id="modalFotoImg" 
                    src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                    alt="Últimas fotos tiradas associadas ao alarme" 
                    class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="historico.js"></script>
</body>
</html>