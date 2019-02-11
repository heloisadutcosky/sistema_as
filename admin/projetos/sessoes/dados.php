<?php 
	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	// Abrir consulta ao banco de dados
	if (isset($_GET["codigo"])) {
		$projeto_id = $_GET["codigo"];
		$produto = $_GET["produto"];

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $projeto_id;
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_num_rows($acesso);

		// Informações preenchidas para exclusão dos dados ------------------------------
		if (isset($_GET["amostra"])) {
			$acao = $_GET["acao"];
			$sessao = $_GET["sessao"];
			$amostra_codigo = $_GET["amostra"];

			// Excluir cadastro ---------------------------------------------------------
			if ($acao == "exclusao") {
					
				$excluir = "DELETE FROM amostras WHERE projeto_id = {$projeto_id} AND sessao = {$sessao} AND amostra_codigo = '{$amostra_codigo}'";

				echo $excluir;

				$operacao_excluir = mysqli_query($conecta, $excluir);

				if (!$operacao_excluir) {
					die("Falha na exclusão dos dados.");
				} else {
					header("location:dados.php?codigo={$projeto_id}&produto={$produto}");
				}
			}
			// --------------------------------------------------------------------------
				}

		if ($dados>0) {

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulário</title>
	
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
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco">Sessões e amostras - <?php echo $produto; ?></h2>
			<br>

			<div class="botao">
				<a href="painel.php?acao=cadastro&codigo=<?php echo($projeto_id); ?>&produto=<?php echo $produto; ?>">Adicionar sessão ou amostra</a>
			</div>
			<br>
			
			<div id="cima_tabela" style="width: 345px">
				<ul>
				    <li style="width: 70px"><b>Sessão</b></li>
				    <li style="width: 100px"><b>Amostra</b></li>
				    <li style="width: 70px"><b>Código</b></li>
				</ul>
			</div>
			<div id="janela" style="width: 345px">
				<?php
				    while($linha = mysqli_fetch_assoc($acesso)) {
				?>
				<ul>
				    <li style="width: 70px"><?php echo utf8_encode($linha["sessao"]) ?></li>
				    <li style="width: 100px"><?php echo utf8_encode($linha["amostra_descricao"]) ?></li>
				    <li style="width: 70px"><?php echo utf8_encode($linha["amostra_codigo"]) ?></li>
				    <li style="width: 0px"></li>
				    <li style="width: 70px"><a href="dados.php?acao=exclusao&codigo=<?php echo $linha["projeto_id"] ?>&produto=<?php echo $produto; ?>&sessao=<?php echo $linha["sessao"]; ?>&amostra=<?php echo $linha["amostra_codigo"]; ?>">Excluir</a> </li>
				</ul>
				<?php } ?>	
			</div>
			<br><br>
		</article>
		

		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php } else { ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulário</title>
	
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
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2 class="espaco">Sessões e amostras - <?php echo $produto; ?></h2>

		<p style="margin-left: 15px">Ainda não existe uma sessão cadastrada para esse projeto</p><br><br>

		<div class="botao">
			<a href="painel.php?acao=cadastro&codigo=<?php echo($projeto_id); ?>&produto=<?php echo $produto; ?>">Adicionar sessão</a>
		</div>
		<br><br><br><br><br><br><br><br>
		</article>
		
		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>


<?php } 
} ?>
