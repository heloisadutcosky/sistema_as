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
	$atributo_completo = isset($_GET["atributo_completo"]) ? $_GET["atributo_completo"] : "";

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

	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND atributo_completo = '{$atributo_completo}'";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["atributo"])) {
		$conjunto_atributos = utf8_decode($_POST["conjunto_atributos"]);
		$descricao_conjunto = utf8_decode($_POST["descricao_conjunto"]);
		$atributo = utf8_decode($_POST["atributo"]);
		$atributo_short = $_POST["atributo_short"];
		$atributo_completo = strtolower($_POST["conjunto_atributos"]) . "_" . strtolower($_POST["atributo_short"]);
		$escala_baixo = utf8_decode($_POST["escala_baixo"]);
		$escala_alto = utf8_decode($_POST["escala_alto"]);
		$escala_min = $_POST["escala_min"];
		$escala_max = $_POST["escala_max"];

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE formularios SET projeto_id = {$projeto_id}, conjunto_atributos = '{$conjunto_atributos}', descricao_conjunto = '{$descricao_conjunto}', atributo = '{$atributo}', atributo_short = '{$atributo_short}', atributo_completo = '{$atributo_completo}', escala_baixo = '{$escala_baixo}', escala_alto = '{$escala_alto}', escala_min = '{$escala_min}', escala_max = '{$escala_max}' WHERE projeto_id = {$projeto_id} AND conjunto_atributos = '{$conjunto_atributos}' AND atributo = '{$atributo}'";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php?codigo={$projeto_id}&produto={$produto}");
			}
		}
		// --------------------------------------------------------------------------

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------

			$consulta_atributo = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND atributo_completo '{$atributo_completo}'";

			$acesso = mysqli_query($conecta, $consulta_atributo);
			$existe_atributo = mysqli_fetch_assoc($acesso);

			if (!empty($existe_atributo)) { ?>
				<p>Esse atributo já foi cadastrado nesse projeto</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO formularios (projeto_id, conjunto_atributos, descricao_conjunto, atributo, atributo_short, atributo_completo, escala_baixo, escala_alto, escala_min, escala_max) VALUES ($projeto_id, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$atributo_short', '$atributo_completo', '$escala_baixo', '$escala_alto', '$escala_min', '$escala_max')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro dos dados.");
				} else {
					header("location:dados.php?codigo={$projeto_id}&produto={$produto}");
				}
			}
		}
		// --------------------------------------------------------------------------
		

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM formularios WHERE projeto_id = {$projeto_id} AND atributo_completo = '{$atributo_completo}'";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php?codigo={$projeto_id}&produto={$produto}");
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
	<title>Atributos</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE ATRIBUTO";
			elseif ($acao == "exclusao") echo "EXCLUSÃO DE ATRIBUTO";
			else echo "CADASTRO DE ATRIBUTO"; 
			?></h2>


		<h3 style="margin: 30px 0; color: #8B0000"><b>Formulário <?php echo $produto; ?></b></h3>

		
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>" method="post">

			<p><b>Informações do conjunto de atributos: </b></p>

			<div>
				<label for="conjunto_atributos">Nome do conjunto de atributos: </label>
				<input type="text" id="conjunto_atributos" name="conjunto_atributos" value="<?php echo utf8_encode($dados["conjunto_atributos"]); ?>" required>
			</div><br>

			<div>
				<label for="descricao_conjunto">Explicação de como avaliá-lo: </label>
				<input type="text" id="descricao_conjunto" name="descricao_conjunto" value="<?php echo utf8_encode($dados["descricao_conjunto"]); ?>" style= "width: 440px; height: 40px;">
			</div><br>
			

			<div style="margin-bottom: 10px;">
				<p style="margin-bottom: 0px;"><b>Informações do atributo avaliado</b><small><sup>*</sup></small>: </p>
				<small style="font-size: 55%; margin-left: 0px;"><sup>*</sup>Preencher um por página</small>
			</div>
			
			<div style="float: left; margin-right: 30px;">
				<label for="atributo">Atributo: </label>
				<input type="text" id="atributo" name="atributo" value="<?php echo utf8_encode($dados["atributo"]); ?>" required>
			</div>

			<div>
				<label for="atributo_short">Nome curto para o atributo<small><sup>**</sup></small>:</label>
				<input type="text" id="atributo_short" name="atributo_short" value="<?php echo $dados["atributo_short"]; ?>" style="margin-bottom: 1px;" required>
				<small style="font-size: 55%; margin-left: 0px; width: 200px"><sup>**</sup>Sem espaços (colunas = conjunto + nome curto)</small>
			</div><br>

			<p><b>Informações dos extremos da escala: </b></p>

			<div style="float: left; margin-right: 30px;">
				<label for="escala_baixo">Texto escala baixa: </label>
				<input type="text" id="escala_baixo" name="escala_baixo" value="<?php echo utf8_encode($dados["escala_baixo"]); ?>">
			</div>
			<div>
				<label for="escala_alto">Texto escala alta: </label>
				<input type="text" id="escala_alto" name="escala_alto" value="<?php echo utf8_encode($dados["escala_alto"]); ?>">
			</div><br>

			<div style="float: left; margin-right: 30px;">
				<label for="escala_min">Valor escala baixa: </label>
				<input type="number" id="escala_min" name="escala_min" value="<?php echo $dados["escala_min"]; ?>">
			</div>
			<div>
				<label for="escala_max">Valor escala alta: </label>
				<input type="text" id="escala_max" name="escala_max" value="<?php echo $dados["escala_max"]; ?>">
			</div><br><br>

			<input type="submit" id="botao" value="<?php 
				if ($acao == "alteracao") echo "Alterar atributo";
				elseif ($acao == "exclusao") echo "Excluir atributo";
				else echo "Cadastrar atributo";
			?>" style="margin-left: 10px">
		</form>
		<br><br>
		</article>

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
