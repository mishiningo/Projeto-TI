<!doctype html>
<html lang="en">
<?php 
  session_start();
  if(!isset($_SESSION['username'])){
    header("refresh:5;url=login.php");
    die("Acesso Restrito");
  }
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="syle.css">
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">DashBoard</a>
		
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="#">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Histórico</a>
				</li>
			</ul>
			<div style="margin-left: 75%;">
			<a href="login.php"><button type="button" class="btn btn-outline-dark">Logout</button></a>
			</div>
		</div>
	</nav>
	
	<div class="container text-center">
		<div class="row">
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header sensor myHomeStyle" >
						<b>Temperatura</b>
					</div>
					
					<div class="card-body">
						
					</div>
					<div class="card-footer">
						<b>Atualização:</a>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header sensor myHomeStyle">
						<b>Humidade:</b>
					</div>
					
					<div class="card-body">
						
					</div>
					<div class="card-footer">
						<b>Atualização:</a>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header atuador myHomeStyle">
						<b>Led Arduino:</b>
					</div>
					
					<div class="card-body">
						
					</div>
					<div class="card-footer">
						<b>Atualização:</a>
					</div>
				</div>
			</div>	
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header sensor myHomeStyle">
						<b>Imagens</b>
					</div>
					<div class="card-body">
						
						
					</div>
					<div class="card-footer">
						<b>Atualização:</a>
					</div>
				</div>
			</div>
	</div>



        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
 </body>
</html>