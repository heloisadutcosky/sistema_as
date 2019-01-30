<?php

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";

	// Abrir consulta ao banco de dados para pegar informações do usuário selecionado
	if (isset($_GET["codigo"])) {
		$projeto_id = $_GET["codigo"];
		$_SESSION["projeto_id"] = $projeto_id;
	} else {
		$projeto_id = 0;
	}

	$consulta = "SELECT * FROM projetos WHERE projeto_id = {$projeto_id}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["empresa"])) {
		$projeto_id = $_SESSION["projeto_id"];
		$empresa = $_POST["empresa"];
		$produto = $_POST["produto"];
		$descricao_projeto = $_POST["descricao_projeto"];
		$tipo_avaliacao = $_POST["tipo_avaliacao"];
		$escala_min = $_POST["escala_min"];
		$escala_max = $_POST["escala_max"];
		$data_inicio = $_POST["data_inicio"];
		$data_fim = $_POST["data_fim"];
		$form_ativo = isset($_POST["form_ativo"]) ? 1 : 0;
		$tipo_consumidor = $_POST["tipo_consumidor"];

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE projetos SET empresa = '{$empresa}', produto = '{$produto}', descricao_projeto = '{$descricao_projeto}', tipo_avaliacao = '{$tipo_avaliacao}', escala_min = {$escala_min}, escala_max = {$escala_max}, data_inicio = '{$data_inicio}', data_fim = '{$data_fim}', form_ativo = {$form_ativo}, tipo_consumidor = '{$tipo_consumidor}' WHERE projeto_id = {$projeto_id}";

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

			$consulta_projeto = "SELECT * FROM projetos WHERE empresa = " . $empresa . " AND produto = " . $produto . " AND tipo_avaliacao = " . $tipo_avaliacao;

			$acesso = mysqli_query($conecta, $consulta_projeto);
			$existe_projeto = mysqli_fetch_assoc($acesso);

			if (!empty($existe_projeto)) { ?>
				<p>Esse projeto já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO projetos (empresa, produto, descricao_projeto, tipo_avaliacao, escala_min, escala_max, data_inicio, data_fim, form_ativo, tipo_consumidor) VALUES ('$empresa', '$produto', '$descricao_projeto', '$tipo_avaliacao', $escala_min, $escala_max, '$data_inicio', '$data_fim', '$form_ativo', '$tipo_consumidor')";

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
				
			$excluir = "DELETE FROM projetos WHERE projeto_id = {$projeto_id}";

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
	<title>Alteração de usuário</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE CADASTRO DE PROJETO";
			elseif ($acao == "exclusao") echo "EXCLUSÃO DE PROJETO";
			else echo "CADASTRO DE PROJETO"; 
			?></h2>

		<br>
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>" method="post">

			<label for="empresa">Empresa: </label>
			<input type="text" id="empresa" name="empresa" value="<?php echo $dados["empresa"]; ?>" required><br>

			<label for="produto">Produto: </label>
			<input type="text" id="produto" name="produto" value="<?php echo $dados["produto"]; ?>" required><br>

			<label for="tipo_avaliacao">Tipo de avaliação sensorial: </label>
			<input type="text" id="tipo_avaliacao" name="tipo_avaliacao" value="<?php echo $dados["tipo_avaliacao"]; ?>" required><br>

			<label for="tipo_consumidor">Tipo de avaliadores: </label>
			<select id="tipo_consumidor" name="tipo_consumidor"><br>
				<?php switch ($dados["tipo_consumidor"]) {
					case 'Painelista': ?>
						<option value="Consumidor">Consumidor</option>
						<option value="Painelista" selected>Painelista</option>
						<?php break;

					default: ?>
						<option value="Consumidor" selected>Consumidor</option>
						<option value="Painelista">Painelista</option>
						<?php break; 
				}?>
			</select><br><br>

			<label for="escala_min">Escala mínima: </label>
			<input type="number" id="escala_min" name="escala_min" value="<?php echo $dados["escala_min"]; ?>">

			<label for="escala_max">Escala máxima: </label>
			<input type="number" id="escala_max" name="escala_max" value="<?php echo $dados["escala_max"]; ?>"><br>

			<label for="descricao_projeto">Descrição do projeto: </label>
			<input type="text" id="descricao_projeto" name="descricao_projeto" value="<?php echo $dados["descricao_projeto"]; ?>" required><br>

			<label for="data_inicio">Data de início: </label>
			<input type="date" id="data_inicio" name="data_inicio" value="<?php echo $dados["data_inicio"]; ?>">

			<label for="data_fim">Data de fim: </label>
			<input type="date" id="data_fim" name="data_fim" value="<?php echo $dados["data_fim"]; ?>"><br>

			<label for="form_ativo">Habilitar formulário: </label>
			<input type="checkbox" id="form_ativo" name="form_ativo" <?php if ($dados["form_ativo"] == 1) { ?> 
				checked
			<?php } ?>><br>

			<input type="submit" id="botao" value="<?php 
				if ($acao == "alteracao") echo "Alterar cadastro";
				if ($acao == "exclusao") echo "Excluir cadastro";
				if ($acao == "cadastro") echo "Cadastrar";
			?>"><br>
			<br>

		</form>

		<div class="direita">
			<a href="dados.php">Voltar</a><br><br>
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
