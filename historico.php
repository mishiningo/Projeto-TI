<!doctype html>
<html lang="en">
  <head>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estiloLogin.css">
    <meta http-equiv="refresh" content="5">
</head>
  <body>
    <nav class="navbar navbar-expand-sm bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#"><img src=imagens/logo.png width=20%>DashBoard</a>
		
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="alarme.php">Alarme</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="historico.php">Histórico</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="perfil.php">Perfil</a>
				</li>
			</ul>
			<div style="margin-left: 45%;">
			<a href="login.php"><button type="button" class="btn btn-outline-dark">Logout</button></a>
			</div>
		</div>
	</nav>
  <h1 style="text-align: center; margin-top: 20px;">Histórico de Acessos</h1>
    <table class="bordered" style="width:25%; margin-top: 20px; margin-left: 7.5%;  margin-left: auto; margin-right: auto;">
        <tr class="myHomeStyle">
            <th style="border: 1px solid black;">Usuario:</th>
            <th style="border: 1px solid black;">Data e Hora:</th>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Leo</td>
            <td style="border: 1px solid black;">2025-02-15 -- 10:30:00</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Maria</td>
            <td style="border: 1px solid black;">2025-02-16 -- 14:45:00</td>
        </tr>
    </table>
  <h1 style="text-align: center; margin-top: 20px;">Histórico de Imagens</h1>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>