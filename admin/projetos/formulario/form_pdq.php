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
	$atributo_id = isset($_GET["atributo_id"]) ? $_GET["atributo_id"] : 0;

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

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["atributo"])) {
		$conjunto_atributos = utf8_decode($_POST["conjunto_atributos"]);
		$descricao_conjunto = utf8_decode($_POST["descricao_conjunto"]);
		$atributo = utf8_decode($_POST["atributo"]);
		$definicao_atributo = utf8_decode($_POST["definicao_atributo"]);
		$atributo_completo = utf8_decode($_POST["atributo_completo"]);
		$atributo_completo2 = utf8_decode($_POST["atributo_completo2"]);
		$escala_baixo = utf8_decode($_POST["escala_baixo"]);
		$escala_alto = utf8_decode($_POST["escala_alto"]);
		$escala_min = !empty($_POST["escala_min"]) ? $_POST["escala_min"] : 0;
		$escala_max = !empty($_POST["escala_max"]) ? $_POST["escala_max"] : 0;
		$referencia_min = utf8_decode($_POST["referencia_min"]);
		$referencia_max = utf8_decode($_POST["referencia_max"]);
		$img_min = utf8_decode($_POST["img_min"]);
		$img_max = utf8_decode($_POST["img_max"]);

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE formularios SET projeto_id = {$projeto_id}, conjunto_atributos = '{$conjunto_atributos}', descricao_conjunto = '{$descricao_conjunto}', atributo = '{$atributo}', definicao_atributo = '{$definicao_atributo}', atributo_completo = '{$atributo_completo}', atributo_completo2 = '{$atributo_completo2}', escala_baixo = '{$escala_baixo}', escala_alto = '{$escala_alto}', escala_min = {$escala_min}, escala_max = {$escala_max}, referencia_min = '{$referencia_min}', referencia_max = '{$referencia_max}', img_min = '{$img_min}', img_max = '{$img_max}' WHERE atributo_id = {$atributo_id}";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php?codigo={$projeto_id}&produto={$produto}&avaliacao=pdq");
			}
		}
		// --------------------------------------------------------------------------

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------

			$consulta_atributo = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND atributo_completo = '{$atributo_completo}'";

			$acesso = mysqli_query($conecta, $consulta_atributo);
			$existe_atributo = mysqli_fetch_assoc($acesso);

			if (!empty($existe_atributo)) { ?>
				<p>Esse atributo já foi cadastrado nesse projeto</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO formularios (projeto_id, conjunto_atributos, descricao_conjunto, atributo, definicao_atributo, atributo_completo, atributo_completo2, escala_baixo, escala_alto, escala_min, escala_max, referencia_min, referencia_max, img_min, img_max) VALUES ($projeto_id, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$definicao_atributo', '$atributo_completo', '$atributo_completo2', '$escala_baixo', '$escala_alto', '$escala_min', '$escala_max', '$referencia_min', '$referencia_max', '$img_min', '$img_max')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					echo $cadastrar;
				} else {
					header("location:dados.php?codigo={$projeto_id}&produto={$produto}&avaliacao=pdq");
				}
			}
		}
		// --------------------------------------------------------------------------

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM formularios WHERE atributo_id = {$atributo_id}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php?codigo={$projeto_id}&produto={$produto}&avaliacao=pdq");
			}
		}
		// --------------------------------------------------------------------------
	}
	// ------------------------------------------------------------------------------

	// Liberar dados da memória
	$consulta = "SELECT * FROM formularios WHERE atributo_id = {$atributo_id}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------
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

		
		<form action="form_pdq.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>&atributo_id=<?php echo $atributo_id; ?>" method="post">

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
				<label for="atributo_completo">Nome 1 para a planilha:<small><sup>**</sup></small>:</label>
				<input type="text" id="atributo_completo" name="atributo_completo" value="<?php echo $dados["atributo_completo"]; ?>" style="margin-bottom: 1px;" required>
				<small style="font-size: 55%; margin-left: 0px; width: 200px"><sup>**</sup>Nome em inglês para cliente</small>
			</div><br>

			<div>
				<label for="atributo_completo2">Nome 2 para a planilha:<small><sup>***</sup></small>:</label>
				<input type="text" id="atributo_completo2" name="atributo_completo2" value="<?php echo $dados["atributo_completo2"]; ?>" style="margin-bottom: 1px;">
				<small style="font-size: 55%; margin-left: 10px; width: 200px"><sup>***</sup>Nome em português para painelistas</small>
			</div><br>			

			<div>
				<label for="descricao_conjunto">Definicao do atributo: </label>
				<input type="text" id="definicao_atributo" name="definicao_atributo" value="<?php echo utf8_encode($dados["definicao_atributo"]); ?>" style= "width: 440px; height: 40px;">
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
				<input type="number" id="escala_max" name="escala_max" value="<?php echo $dados["escala_max"]; ?>">
			</div><br>

			<div style="float: left; margin-right: 30px;">
				<label for="referencia_min">Referência escala baixa: </label>
				<input type="text" id="referencia_min" name="referencia_min" value="<?php echo utf8_encode($dados["referencia_min"]); ?>">
			</div>
			<div>
				<label for="referencia_max">Referência escala baixa: </label>
				<input type="text" id="referencia_max" name="referencia_max" value="<?php echo utf8_encode($dados["referencia_max"]); ?>">
			</div><br>

			<div style="float: left; margin-right: 30px;">
				<label for="img_min">Imagem referência escala baixa: </label>
				<input type="text" id="img_min" name="img_min" value="<?php echo $dados["img_min"]; ?>">
			</div>
			<div>
				<label for="img_max">Imagem referência escala baixa: </label>
				<input type="text" id="img_max" name="img_max" value="<?php echo $dados["img_max"]; ?>">
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
			<a href="dados.php?codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>&avaliacao=pdq">Voltar</a><br><br>
		</div>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
if (isset($operacao_cadastrar)) {
	mysqli_free_result($operacao_cadastrar);
}
if (isset($operacao_alterar)) {
	mysqli_free_result($operacao_alterar);
}
if (isset($operacao_excluir)) {
	mysqli_free_result($operacao_excluir);
}
	mysqli_close($conecta);
?>