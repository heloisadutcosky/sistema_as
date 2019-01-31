<?php

	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";
	$projeto_id = isset($_GET["codigo"]) ? $_GET["codigo"] : 0;
	$produto = isset($_GET["produto"]) ? $_GET["produto"] : "";

	// Abrir consulta ao banco de dados para pegar informações do projeto selecionado
	if (isset($_GET["amostra"])) {
		$sessao = $_GET["sessao"];
		$amostra_codigo = $_GET["amostra"];
	} else {
		$sessao = 0;
		$amostra_codigo = "";
	}

	$consulta = "SELECT * FROM amostras WHERE projeto_id = {$projeto_id} AND sessao = {$sessao} AND amostra_codigo = '{$amostra_codigo}'";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["amostra_descricao"])) {
		$sessao = $_POST["sessao"];
		$data = $_POST["data"];
		$amostra_descricao = utf8_decode($_POST["amostra_descricao"]);
		$amostra_codigo = $_POST["amostra_codigo"];

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			$cadastrar = "INSERT INTO amostras (projeto_id, sessao, data, amostra_descricao, amostra_codigo) VALUES ($projeto_id, $sessao, '$data', '$amostra_descricao', '$amostra_codigo')";

			$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

			if (!$operacao_cadastrar) {
				die("Falha no cadastro dos dados.");
			} else {
				header("location:dados.php?codigo=" . $projeto_id . "&produto=" . $produto);
			}
		}
		// --------------------------------------------------------------------------

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM amostras WHERE projeto_id = {$projeto_id} AND sessao = {$sessao} AND amostra_codigo = {$amostra_codigo}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php?codigo=" . $projeto_id . "&produto=" . $produto);
			}
		}
		// --------------------------------------------------------------------------
	}
	// ------------------------------------------------------------------------------

	// Liberar dados da memória
	mysqli_free_result($acesso);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE AMOSTRA";
			elseif ($acao == "exclusao") echo "EXCLUSÃO DE AMOSTRA";
			else echo "CADASTRO DE AMOSTRA"; 
			?></h2>


		<h3 style="margin: 30px 0; color: #8B0000"><b>Sessões e amostras - <?php echo $produto; ?></b></h3>

		
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>&sessao=<?php echo $sessao; ?>&amostra=<?php echo $amostra_codigo; ?>" method="post">

			<label for="sessao">Sessão: </label>
			<input type="number" id="sessao" name="sessao" value="<?php echo $dados["sessao"]; ?>" required><br>

			<label for="data">Data: </label>
			<input type="date" id="data" name="data" value="<?php echo $dados["data"]; ?>" required><br>
			
			<p>Favor cadastrar as amostras e os códigos que serão utilizados</p>
			
			<label for="amostra_descricao">Descrição: </label>
			<input type="text" id="amostra_descricao" name="amostra_descricao" value="<?php echo utf8_encode($dados["amostra_descricao"]); ?>" required><br>

			<label for="amostra_codigo">Código: </label>
			<input type="text" id="amostra_codigo" name="amostra_codigo" value="<?php echo $dados["amostra_codigo"]; ?>" required><br>

			<input type="submit" id="botao" value="<?php 
				if ($acao == "alteracao") echo "Alterar amostra";
				elseif ($acao == "exclusao") echo "Excluir amostra";
				else echo "Cadastrar amostra"; 
				?>">
		</form>

		<div class="direita">
			<a href="dados.php?codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>">Voltar</a><br><br>
		</div>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
