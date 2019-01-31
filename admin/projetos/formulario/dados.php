<?php 

	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$produto = isset($_GET["produto"]) ? $_GET["produto"] : "";

	// Abrir consulta ao banco de dados
	if (isset($_GET["codigo"])) {
		$projeto_id = $_GET["codigo"];

		$consulta = "SELECT * FROM formularios WHERE projeto_id = " . $projeto_id;
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_num_rows($acesso);

		if ($dados>0) {

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulários</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">

	<style type="text/css">
		li a {
	    list-style:none;
	    display:inline-block;
	    background-color: none;	    
	}

	div#janela li:nth-child(1) {
	    padding:5px 5px;
	}

	div#janela li:nth-child(2) {
	    width:280px;  
	    padding:5px 2px;
	}    

	div#janela li:nth-child(3) {
	    width:0px;  
	    padding:5px 2px;
	}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco">Formulário - <?php echo $produto; ?></h2>
		<br>

		<div class="botao">
			<a href="painel.php?acao=cadastro&codigo=<?php echo $projeto_id ?>&produto=<?php echo $produto; ?>">Adicionar atributo</a>
		</div>
		<br>

		<div id="cima_tabela" class="usuarios">
			<ul>
			    <li><b>Conjunto</b></li>
			    <li><b>Atributos</b></li>
			    <li><b></b></li>
			</ul>
		</div>
		<div id="janela" class="usuarios">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li><?php echo utf8_encode($linha["conjunto_atributos"]) ?></li>
			    <li><?php echo utf8_encode($linha["atributo"]) ?></li>
			    <li></li>
			    <li></li>
			    <li><a href="painel.php?acao=exclusao&codigo=<?php echo $linha["projeto_id"] ?>&produto=<?php echo $produto; ?>&atributo_completo=<?php echo  $linha["atributo_completo"]; ?>">Excluir</a> </li>
			</ul>
			<?php } ?>	
		</div>

		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>
		<br>
		<br>		

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php } else { ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulários</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">

	<style type="text/css">
		li a {
	    list-style:none;
	    display:inline-block;
	    background-color: none;	    
	}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco">Formulário - <?php echo $produto; ?></h2>

		<p>Ainda não existe um formulário pra esse projeto</p><br><br>

		<div class="botao">
			<a href="painel.php?acao=cadastro&codigo=<?php echo $projeto_id ?>&produto=<?php echo $produto; ?>">Adicionar formulário</a>
		</div>
		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>
		<br>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>


<?php } 
} ?>
