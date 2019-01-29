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

	$consulta = "SELECT * FROM formularios" . $complemento;
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
				
			$alterar = "UPDATE projetos SET projeto_id = {$projeto_id}, conjunto_atributos = '{$conjunto_atributos}', descricao_conjunto = '{$descricao_conjunto}', atributo = '{$atributo}', atributo_short = '{$atributo_short}', atributo_completo = '{$atributo_completo}', escala_baixo = '{$escala_baixo}', escala_alto = '{$escala_alto}', escala_min = {$escala_min}, escala_max = {$escala_max} WHERE projeto_id = {$projeto_id} AND conjunto_atributos = '{$conjunto_atributos}' AND atributo = '{$atributo}'";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php");
			}
		}
		// --------------------------------------------------------------------------

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------

			$consulta_atributo = "SELECT * FROM formularios WHERE projeto_id = " . $projeto_id . " AND atributo_completo = " . $atributo_completo;

			$acesso = mysqli_query($conecta, $consulta_atributo);
			$existe_atributo = mysqli_fetch_assoc($acesso);

			if (!empty($existe_atributo)) { ?>
				<p>Esse atributo já foi cadastrado nesse projeto</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO formularios (projeto_id, conjunto_atributos, descricao_conjunto, atributo, atributo_short, atributo_completo, escala_baixo, escala_alto, escala_min, escala_max) VALUES ($projeto_id, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$atributo_short', '$atributo_completo', '$escala_baixo', '$escala_alto', $escala_min, $escala_max)";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro dos dados.");
				} else {
					header("location:dados.php");
				}
			}
		}
		// --------------------------------------------------------------------------

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM formularios WHERE AND conjunto_atributos = '{$conjunto_atributos}' AND atributo = '{$atributo}'";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php");
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

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE ATRIBUTO";
			elseif ($acao == "exclusao") echo "EXCLUSÃO DE ATRIBUTO";
			else echo "CADASTRO DE ATRIBUTO"; 
			?></h2>


		<h3 style="margin: 30px 0; color: #8B0000"><b>Formulário <?php echo $produto; ?></b></h3>

		
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>&produto=<?php echo $produto; ?>" method="post">

			<p><b>Favor preencher o conjunto de atributos e uma explicação de como avaliá-lo: </b></p>

			<label for="conjunto_atributos">Conjunto de atributos: </label>
			<input type="text" id="conjunto_atributos" name="conjunto_atributos" value="<?php echo utf8_encode($dados["conjunto_atributos"]); ?>" required><br>

			<label for="descricao_conjunto">Explicação: </label>
			<input type="text" id="descricao_conjunto" name="descricao_conjunto" value="<?php echo utf8_encode($dados["descricao_conjunto"]); ?>" style= "width: 400px; height: 40px;"><br>
			

			<p><b>Favor preencher o atributo avaliado: </b></p>
			
			<label for="atributo">Atributo: </label>
			<input type="text" id="atributo" name="atributo" value="<?php echo utf8_encode($dados["atributo"]); ?>" required>
			<small style="font-size: 60%; margin-left: -18px"><sup>*</sup> Um por página</small><br>

			<label for="atributo_short">Nome curto para o atributo:</label>
			<input type="text" id="atributo_short" name="atributo_short" value="<?php echo $dados["atributo_short"]; ?>" required>
			<small style="font-size: 60%; margin-left: -18px"><sup>*</sup>Sem espaços (os nomes das colunas serão formados pelo nome do conjunto de atributos mais o nome curto do atributo)</small><br><br>

			<p><b>Favor preencher os textos que devem aparecer nos extremos da régua e os valores correspondentes: </b></p>

			<label for="escala_baixo">Escala baixa: </label>
			<input type="text" id="escala_baixo" name="escala_baixo" value="<?php echo utf8_encode($dados["escala_baixo"]); ?>">
			<label for="escala_alto">Escala alta: </label>
			<input type="text" id="escala_alto" name="escala_alto" value="<?php echo utf8_encode($dados["escala_alto"]); ?>"><br>

			<label for="escala_min">Valor escala baixa: </label>
			<input type="number" id="escala_min" name="escala_min" value="<?php echo $dados["escala_min"]; ?>">
			<label for="escala_max">Valor escala alta: </label>
			<input type="text" id="escala_max" name="escala_max" value="<?php echo $dados["escala_max"]; ?>"><br><br>

			<input type="submit" id="botao" value="<?php 
				if ($acao == "alteracao") echo "Alterar atributo";
				elseif ($acao == "exclusao") echo "Excluir atributo";
				else echo "Cadastrar atributo";
			?>">

		</form>

		<div class="direita">
			<a href="dados.php?codigo=<?php echo $projeto_id; ?>">Voltar</a><br><br>
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
