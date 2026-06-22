<!doctype html>
<html lang="pt">
<?php 
	require_once 'auth.php';
	if(!isset($_SESSION['username'], $_SESSION['nivel'])){
    	header("refresh:5;url=login.php");
    	die("Acesso Restrito");
  }

	$estado = file_get_contents("API/files/Alarme/estado.txt");
	$hora = file_get_contents("API/files/Alarme/hora.txt");
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="5">
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
	<div class="container">
		<div class="row myHomeStyle text-center my-3 d-flex justify-content-center"> 
			<div class="welcome rounded-border pt-2">
			<?php 
				echo "<h3>Seja bem vindo, " . $_SESSION['username'] . "!</h3>";
			?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="card text-center">
					<div class="card-header">
						Estado do alarme: <b>
						<?php 
							echo htmlspecialchars($estado);
						?> </b>
					</div>
					<div class="card-body">
						<?php if($estado == "Ativo"){
                            echo "<img src=\"imagens/AlarmeON.png\" class='imgAlarme'>";
                        } else if ($estado == "Desativado") {
                            echo "<img src=\"imagens/AlarmeOFF.png\" class='imgAlarme'>";
                        } else {
							echo "<img src=\"imagens/AlarmeHIT.png\" class='imgAlarme'>";
						}?>
					</div>
					<div class="card-footer">
						Data e hora da última atualização: 
						<b>
						<?php 
							echo htmlspecialchars($hora);
						?> </b>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">	
						<b>Controlo do alarme</b>
					</div>
					<div class="card-body">
						<table>
							<tr>
								<td> Botão para desativar por 30s </td>
								<td> Botão para desativar por 30m</td>
								<td> Botão para desativar indefinidamente</td>								
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	


        
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</html>