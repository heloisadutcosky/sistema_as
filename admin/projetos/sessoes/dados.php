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

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $projeto_id;
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
		<h2 class="espaco">Sessões e amostras - <?php echo $produto; ?></h2>

		
		<div id="cima_tabela" class="usuarios">
			<ul>
			    <li><b>SESSÃO</b></li>
			    <li><b>AMOSTRA</b></li>
			    <li><b>CÓDIGO</b></li>
			</ul>
		</div>
		<div id="janela" class="usuarios">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li><?php echo utf8_encode($linha["sessao"]) ?></li>
			    <li><?php echo utf8_encode($linha["amostra_descricao"]) ?></li>
			    <li><?php echo utf8_encode($linha["amostra_codigo"]) ?></li>
			    <li><a href="alteracao.php?codigo=<?php echo $linha["projeto_id"] ?>">Alterar</a> </li>
			    <li><a href="exclusao.php?codigo=<?php echo $linha["projeto_id"] ?>">Excluir</a> </li>
			</ul>
			<?php } ?>	
		</div>
		<br>

		<div class="botao">
			<a href="cadastro.php?codigo=<?php echo($projeto_id); ?>">Adicionar sessão ou amostra</a>
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
		<h2 class="espaco">Sessões e amostras - <?php echo $produto; ?></h2>

		<p>Ainda não existe uma sessão cadastradas pra esse projeto</p><br><br>

		<div class="botao">
			<a href="cadastro.php">Adicionar sessão</a>
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
