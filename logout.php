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
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
			<img src="http://aboutsolution.com.br/novo/wp-content/uploads/2017/12/Logo_About-Solution.png" width="210" height="70"
				title="logo About Solution">
			</a>
		</header>
		
		<h2 class="espaco"></h2>
		<br>

		<?php if (isset($_GET["mensagem"])){ ?>
			<p>Você finalizou a análise com sucesso.</p>
			<p>Muito obrigado!</p>
			<br>
		<?php } ?>

		<p>Volte sempre à About Solution!</p>

		<a href="login.php">Login</a>
		<br>
		<br>
		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>