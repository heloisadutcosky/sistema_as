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
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">

</head>
<body>
<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<p>Bem vindo(a), <?php echo utf8_encode($_SESSION["usuario"]); ?>! <u>O que você deseja fazer?</u></p>
			<br><br>
			<img src="<?php echo($caminho); ?>/_imagens/fluxo.png" width="650" height="200"
		title="Fluxo de informações"><br><br><br><br><br><br>
		</article>
		

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>