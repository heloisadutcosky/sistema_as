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

	<style type="text/css">
		.menu {
			display: inline-block;
		}
	</style>

</head>
<body>
<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<p>Bem vindo(a), <?php echo utf8_encode($_SESSION["usuario"]); ?>! <u>O que você deseja fazer?</u></p>
			<br><br>


			<?php 
			if ($_SESSION["cpf"] == "08825096917" || $_SESSION["cpf"] == "51226537987") { ?>
			<p>Em qual tabela você deseja mexer?</p>
			<ul>
				<li class="menu"><a href="<?php echo $caminho; ?>conexao/conexao.php?tabela=about_solution" style="<?php if ($_SESSION["tabela"] == "about_solution") {echo "background-color: #F99B95"; }?>">Tabela About Solution</a></li>
				<li class="menu"><a href="<?php echo $caminho; ?>conexao/conexao.php?tabela=demo" style="<?php if ($_SESSION["tabela"] == "demo") {echo "background-color: #F99B95"; }?>">Tabela demonstrativa</a></li>
			</ul>
			<?php } ?>

			<br><br><br><br>
			<br><br><br><br><br><br>
			<br><br><br><br><br>
		</article>
		

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>