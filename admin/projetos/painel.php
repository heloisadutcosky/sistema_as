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
		$empresa = utf8_decode($_POST["empresa"]);
		$produto_id = utf8_decode($_POST["produto_id"]);
		$descricao_projeto = utf8_decode($_POST["descricao_projeto"]);
		$tipo_avaliacao = utf8_decode($_POST["tipo_avaliacao"]);
		$escala_min = $_POST["escala_min"];
		$escala_max = $_POST["escala_max"];
		$data_inicio = $_POST["data_inicio"];
		$data_fim = $_POST["data_fim"];
		$form_ativo = isset($_POST["form_ativo"]) ? 1 : 0;
		$tipo_avaliador = $_POST["tipo_avaliador"];

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE projetos SET empresa = '{$empresa}', produto_id = '{$produto_id}', descricao_projeto = '{$descricao_projeto}', tipo_avaliacao = '{$tipo_avaliacao}', escala_min = {$escala_min}, escala_max = {$escala_max}, data_inicio = '{$data_inicio}', data_fim = '{$data_fim}', form_ativo = {$form_ativo}, tipo_avaliador = '{$tipo_avaliador}' WHERE projeto_id = {$projeto_id}";

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

			$consulta_projeto = "SELECT * FROM projetos WHERE empresa = '{$empresa}' AND produto_id = '{$produto_id}' AND tipo_avaliacao = '{$tipo_avaliacao}'";

			$acesso = mysqli_query($conecta, $consulta_projeto);
			$existe_projeto = mysqli_fetch_assoc($acesso);

			if (!empty($existe_projeto)) { ?>
				<p>Esse projeto já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO projetos (empresa, produto_id, descricao_projeto, tipo_avaliacao, escala_min, escala_max, data_inicio, data_fim, form_ativo, tipo_avaliador) VALUES ('{$empresa}', '{$produto_id}', '{$descricao_projeto}', '{$tipo_avaliacao}', {$escala_min}, {$escala_max}, '{$data_inicio}', '{$data_fim}', '{$form_ativo}', '{$tipo_avaliador}')";

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
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE CADASTRO DE PROJETO";
			elseif ($acao == "exclusao") echo "EXCLUSÃO DE PROJETO";
			else echo "CADASTRO DE PROJETO"; 
			?></h2>

		<br>
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>" method="post">

			<div style="float: left; margin-right: 30px;">
				<label for="empresa">Empresa: </label>
				<input type="text" id="empresa" name="empresa" value="<?php echo utf8_encode($dados["empresa"]); ?>" required>
			</div>

			<div>
			<label for="produto_id">Produto: </label>
			<select id="produto_id" name="produto_id"><br>
				<?php 
				$consulta2 = "SELECT * FROM produtos";
				$acesso2 = mysqli_query($conecta, $consulta2);
				while($linha = mysqli_fetch_assoc($acesso2)) { ?>
					<?php if($dados["produto_id"] == $linha["produto_id"]) { ?>
						<option value="<?php echo $linha["produto_id"]; ?>" selected><?php echo utf8_encode($linha["produto"]); ?></option>
					<?php } else { ?>
						<option value="<?php echo $linha["produto_id"]; ?>"><?php echo utf8_encode($linha["produto"]); ?></option>
					<?php } ?>
				<?php } ?>
			</select>
			<br>
			</div>

			<div>
				<label for="descricao_projeto">Descrição do projeto: </label>
				<input type="text" id="descricao_projeto" name="descricao_projeto" value="<?php echo utf8_encode($dados["descricao_projeto"]); ?>" style="width: 440px; height: 40px; text-indent: 2px"><br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="data_inicio">Data de início: </label>
				<input type="date" id="data_inicio" name="data_inicio" value="<?php echo $dados["data_inicio"]; ?>">
			</div>

			<div>
				<label for="data_fim">Data de fim: </label>
				<input type="date" id="data_fim" name="data_fim" value="<?php echo $dados["data_fim"]; ?>"><br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="tipo_avaliacao">Tipo de avaliação sensorial: </label>
				<input type="text" id="tipo_avaliacao" name="tipo_avaliacao" value="<?php echo utf8_encode($dados["tipo_avaliacao"]); ?>" required>
			</div>

			<div>
				<label for="tipo_avaliador">Tipo de avaliadores: </label>
				<select id="tipo_avaliador" name="tipo_avaliador"><br>
					<?php switch ($dados["tipo_avaliador"]) {
						case 'Painelista': ?>
							<option value="Consumidor">Consumidor</option>
							<option value="Painelista" selected>Painelista</option>
							<?php break;

						default: ?>
							<option value="Consumidor" selected>Consumidor</option>
							<option value="Painelista">Painelista</option>
							<?php break; 
					}?>
				</select><br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="escala_min">Escala mínima: </label>
				<input type="number" id="escala_min" name="escala_min" value="<?php echo $dados["escala_min"]; ?>" size="10" style="width: 80px;">
			</div>

			<div>
				<label for="escala_max">Escala máxima: </label>
				<input type="number" id="escala_max" name="escala_max" value="<?php echo $dados["escala_max"]; ?>" size="10" style="width: 80px;"><br>
			</div>

			<div>
				<label for="form_ativo">Habilitar formulário: </label>
				<input type="checkbox" id="form_ativo" name="form_ativo" <?php if ($dados["form_ativo"] == 1) { ?> 
				checked <?php } ?>><br>
			</div>

				<div>
				<input type="submit" id="botao" value="<?php 
					if ($acao == "alteracao") echo "Alterar cadastro";
					if ($acao == "exclusao") echo "Excluir cadastro";
					if ($acao == "cadastro") echo "Cadastrar";
				?>"><br>
				<br>
			</div>

		</form>
		</article>

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
