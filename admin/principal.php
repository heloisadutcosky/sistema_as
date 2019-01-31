<?php 

	$caminho =  "../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Administração</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<p>Bem vindo(a), <?php echo utf8_encode($_SESSION["usuario"]); ?>! <u>O que você deseja fazer?</u></p>
		</article>

		<nav>
			<ul>
				<li class="menu"><a href="usuarios/dados.php">Consultar usuários</a></li>
				<li class="menu"><a href="projetos/dados.php">Consultar projetos</a></li>
				<li class="menu"><a href="produtos/dados.php">Consultar produtos</a></li>
				<li class="menu"><a href="<?php echo($caminho); ?>public/principal.php?funcao=Painelista">Revisar questionários</a></li>
				<li class="menu"><a href="resultados.php">Visualizar resultados</a></li>
			</ul>
		</nav>
		<br>

		<nav>
			<ul>
			</ul>
		</nav>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>