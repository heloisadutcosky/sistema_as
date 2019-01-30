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

	// Abrir consulta ao banco de dados para pegar informações do projeto selecionado
	if (isset($_GET["codigo"])) {
		$projeto_id = $_GET["codigo"];
		$_SESSION["projeto_id"] = $projeto_id;
		$produto = $_GET["produto"];
		$complemento = " WHERE projeto_id = {$projeto_id}";
	} else {
		$projeto_id = 0;
		$produto = "";
		$complemento = "";
	}

	$consulta = "SELECT * FROM amostras" . $complemento;
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
		$amostra_descricao = $_POST["amostra_descricao"];
		$amostra_codigo = $_POST["amostra_codigo"];

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE amostras SET projeto_id = {$projeto_id}, sessao = {$sessao}, data = '{$data}', amostra_descricao = '{$amostra_descricao}', amostra_codigo = '{$amostra_codigo}' WHERE projeto_id = {$projeto_id} AND sessao = {$sessao}";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php?codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>");
			}
		}
		// --------------------------------------------------------------------------

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência da sessão na base ------------------------------

			$consulta_sessao = "SELECT * FROM amostras WHERE projeto_id = " . $projeto_id . " AND sessao = " . $sessao;

			$acesso = mysqli_query($conecta, $consulta_sessao);
			$existe_sessao = mysqli_fetch_assoc($acesso);

			if (!empty($existe_sessao)) { ?>
				<p>Essa sessão já foi cadastrada nesse projeto</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO amostras (projeto_id, sessao, data, amostra_descricao, amostra_codigo) VALUES ($projeto_id, $sessao, '$data', '$amostra_descricao', '$amostra_codigo')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro dos dados.");
				} else {
					header("location:dados.php?codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>");
				}
			}
		}
		// --------------------------------------------------------------------------

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM amostras WHERE projeto_id = {$projeto_id} AND sessao = {$sessao}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php?codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>");
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

		
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>" method="post">

			<label for="sessao">Sessão: </label>
			<input type="number" id="sessao" name="sessao" value="<?php echo $dados["sessao"]; ?>" required><br>

			<label for="data">Data: </label>
			<input type="date" id="data" name="data" value="<?php echo $dados["data"]; ?>" required><br>
			
			<p>Favor cadastrar as amostras e os códigos que serão utilizados</p>
			
			<label for="amostra_descricao">Descrição: </label>
			<input type="text" id="amostra_descricao" name="amostra_descricao" value="<?php echo $dados["amostra_descricao"]; ?>" required><br>

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
