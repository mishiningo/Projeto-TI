<!DOCTYPE html>
<html lang="pt">
<?php 
	require_once 'auth.php';
	if(!isset($_SESSION['username'], $_SESSION['nivel'])){
    	header("refresh:5;url=login.php");
    	die("Acesso Restrito");
  }

	$estado = file_get_contents("API/files/alarme/estado.txt");
	$hora = file_get_contents("API/files/alarme/hora.txt");
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-sm turquesa px-4">
    <span class="navbar-brand text-white fw-bold">My Home Stats</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto">
            <li class="nav-item active">
                <a href="dashboard.php" class="nav-link active">Home</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Imagens</a>
            </li>
			<?php
				if($_SESSION['nivel'] == "admin"){
					echo "<li class=\"nav-item\">
						<a href=\"perfis.php\" class=\"nav-link\">Perfis</a>
					</li>";
				} 
			?>
            <li class="nav-item">
                <a href="#" class="nav-link">Histórico</a>
            </li>
        </ul>
        <a href="logout.php">
            <button type="button" class="btn btn-logout">Logout</button>
        </a>
    </div>
    </nav>
	<div class="container-sm">
		<div class="row myHomeStyle text-center my-3 d-flex justify-content-center"> 
			<div class="welcome rounded-border pt-2">
			<?php 
				echo "<h3>Seja bem vindo, " . $_SESSION['username'] . "!</h3>";
			?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-4">
				<div class="card h-100 text-center">
					<div class="card-header">
						Estado do alarme: 
						<b id="label-estado">
						</b>
					</div>
					<div class="card-body" id="imagem-alarme">
					</div>
					<div class="card-footer">
						Data e hora da última atualização: 
						<b id="hora-alarme">
						</b>
					</div>
				</div>
			</div>
			<br>
			<br>
			<div class="col-md-6 mb-4">
				<div class="card h-100 text-center">
					<div class="card-header">	
						<b>Controlo do alarme</b>
					</div>
					<div class="card-body">
						<form method="POST"  action="API/api.php">
							<input type="hidden" name="origem" value="dashboard">
							<input type="hidden" name="nome" value="alarme">
							<input type="hidden" name="hora" value="<?php echo date('Y-m-d H:i:s'); ?>">
							<table id="controlo-alarme">
							</table>
						</form>
					</div>
					<div class="card-footer">
						Clique para alterar o estado do alarme
					<div>
				</div>
			</div>
		</div>
	</div>
	


        
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
	<script>
    // Valor do nivel enviado para js
	// Aspas para ser interpretado como uma string
    const nivelUtilizador = "<?php echo $_SESSION['nivel']; ?>";
	</script>
	<!-- Chamada do script -->
	<script src="async.js"></script>
</html>