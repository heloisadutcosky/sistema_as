<?php 


// Logout

session_start();

session_destroy(); // Destrói todas as variáveis de sessão

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Início</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once("_incluir/topo.php"); ?>
		<h2 class="espaco"></h2>
		<br>

		<p>Você finalizou a análise com sucesso.</p>
		<p>Muito obrigado!</p>
		<br>

		<a href="login.php">Login</a>
		<br>
		<br>
		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>