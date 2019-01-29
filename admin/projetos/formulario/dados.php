<?php 
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once("../../../_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once("../../../conexao/conexao.php");

	// Abrir consulta ao banco de dados
	if (isset($_GET["codigo"])) {
		$projeto_id = $_GET["codigo"];
		$produto = $_GET["produto"];

		$consulta = "SELECT * FROM formularios WHERE projeto_id = " . $projeto_id;
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_num_rows($acesso);

		if ($dados>0) {

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulário</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../../../_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="../../../_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="../../../_css/estilo_tabelas_topo.css">

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
		<?php include_once("../../../_incluir/topo.php"); ?>
		<h2 class="espaco">Formulário - <?php echo $produto; ?></h2>

		<div id="cima_tabela" class="usuarios">
			<ul>
			    <li><b>Conjunto</b></li>
			    <li><b>Atributos</b></li>
			    <li><b>Escala</b></li>
			</ul>
		</div>
		<div id="janela" class="usuarios">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li><?php echo utf8_encode($linha["conjunto_atributos"]) ?></li>
			    <li><?php echo utf8_encode($linha["atributo"]) ?></li>
			    <li><?php echo utf8_encode($linha["escala_min"]) ?> - <?php echo utf8_encode($linha["escala_max"]) ?></li>
			    <li><a href="alteracao.php?codigo=<?php echo $linha["projeto_id"] ?>">Alterar</a> </li>
			    <li><a href="exclusao.php?codigo=<?php echo $linha["projeto_id"] ?>">Excluir</a> </li>
			</ul>
			<?php } ?>	
		</div>
		<br>
		<br>

		<div class="botao">
			<a href="cadastro.php?codigo=<?php echo $projeto_id ?>&produto=<?php echo $produto; ?>">Adicionar atributo</a>
		</div>
		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>
		<br>
		<br>		

		<?php include_once("../../../_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php } else { ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulário</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../../../_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="../../../_css/estilo_tabelas.css">

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
		<?php include_once("../../../_incluir/topo.php"); ?>
		<h2 class="espaco">Formulário do projeto <?php echo $projeto_id; ?></h2>

		<p>Ainda não existe um formulário pra esse projeto</p><br><br>

		<div class="botao">
			<a href="cadastro.php?codigo=<?php echo $projeto_id ?>&produto=<?php echo $produto; ?>">Adicionar formulário</a>
		</div>
		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>
		<br>
		<br>

		<?php include_once("../../../_incluir/rodape.php"); ?>

	</main>
</body>
</html>


<?php } 
} ?>
