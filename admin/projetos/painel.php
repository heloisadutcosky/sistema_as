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
	if (isset($_POST["contrato_id"])) {
		$contrato_id = utf8_decode($_POST["contrato_id"]);
		$produto = utf8_decode($_POST["produto"]);
		$url_imagem = utf8_decode($_POST["url_imagem"]);
		$nome_form = utf8_decode($_POST["nome_form"]);
		$descricao_projeto = utf8_decode($_POST["descricao_projeto"]);
		$tipo_avaliacao = utf8_decode($_POST["tipo_avaliacao"]);
		$escala_min = empty($_POST["escala_min"]) ? 0 : $_POST["escala_min"];
		$escala_max = empty($_POST["escala_max"]) ? 0 : $_POST["escala_max"];
		$data_inicio = empty($_POST["data_inicio"]) ? "0000-00-00" : $_POST["data_inicio"];
		$data_fim = empty($_POST["data_fim"]) ? "0000-00-00" : $_POST["data_fim"];
		$form_ativo = isset($_POST["form_ativo"]) ? 1 : 0;
		$tipo_avaliador = $_POST["tipo_avaliador"];
		$consumo_ativo = isset($_POST["consumo_ativo"]) ? 1 : 0;

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE projetos SET contrato_id = {$contrato_id}, produto = '{$produto}', url_imagem = '{$url_imagem}', descricao_projeto = '{$descricao_projeto}', tipo_avaliacao = '{$tipo_avaliacao}', escala_min = '{$escala_min}', escala_max = '{$escala_max}', data_inicio = '{$data_inicio}', data_fim = '{$data_fim}', form_ativo = '{$form_ativo}', tipo_avaliador = '{$tipo_avaliador}', nome_form = '{$nome_form}', consumo_ativo = '{$consumo_ativo}' WHERE projeto_id = {$projeto_id}";

			echo $alterar;

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

			$consulta_projeto = "SELECT * FROM projetos WHERE contrato_id = {$contrato_id} AND produto = '{$produto}' AND tipo_avaliacao = '{$tipo_avaliacao}'";

			$acesso = mysqli_query($conecta, $consulta_projeto);
			$existe_projeto = mysqli_fetch_assoc($acesso);

			if (!empty($existe_projeto)) { ?>
				<p>Esse projeto já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO projetos (contrato_id, produto, url_imagem, descricao_projeto, tipo_avaliacao, escala_min, escala_max, data_inicio, data_fim, form_ativo, tipo_avaliador, nome_form) VALUES ({$contrato_id}, '{$produto}', '{$url_imagem}', '{$descricao_projeto}', '{$tipo_avaliacao}', '{$escala_min}', '{$escala_max}', '{$data_inicio}', '{$data_fim}', '{$form_ativo}', '{$tipo_avaliador}', '{$nome_form}')";

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

			<div>
				<label for="contrato_id">Contrato: </label>
				<select id="contrato_id" name="contrato_id" style="width: 445px;">
				<br>
					<?php 
					$consulta_contratos = "SELECT * FROM contratos";
					$acesso_contratos = mysqli_query($conecta, $consulta_contratos);
					while ($contratos = mysqli_fetch_assoc($acesso_contratos)) { 
						$consulta_empresas = "SELECT * FROM empresas WHERE empresa_id = {$contratos["empresa_id"]}";
						$acesso_empresas = mysqli_query($conecta, $consulta_empresas);
						$empresa = mysqli_fetch_assoc($acesso_empresas);
					?>
					<option value="<?php echo $contratos["contrato_id"]; ?>" 
					<?php 
						if($contratos["contrato_id"] == $dados["contrato_id"]) { ?> 
							selected 
						<?php } ?>>
					<?php echo $empresa["nome_fantasia"]; ?> - 
					<?php echo date("M/Y", strtotime($contratos["data_inicio"])); ?> a 
					<?php echo date("M/Y", strtotime($contratos["data_fim"])); ?>
					</option>
					<?php } ?>
				</select>
			</div><br>

			<div style="float: left; margin-right: 30px;">
				<label for="categoria_id">Categoria: </label>
				<select id="categoria_id" name="categoria_id"><br>
					<?php 
					$consulta2 = "SELECT * FROM categorias";
					$acesso2 = mysqli_query($conecta, $consulta2);
					while($linha = mysqli_fetch_assoc($acesso2)) { ?>
						<?php if($dados["categoria_id"] == $linha["categoria_id"]) { ?>
							<option value="<?php echo $linha["categoria_id"]; ?>" selected><?php echo utf8_encode($linha["categoria"]); ?></option>
						<?php } else { ?>
							<option value="<?php echo $linha["categoria_id"]; ?>"><?php echo utf8_encode($linha["categoria"]); ?></option>
						<?php } ?>
					<?php } ?>
				</select>
				<br>
			</div>

			<div>
				<label for="produto">Produto: </label>
				<input type="text" id="produto" name="produto" value="<?php echo utf8_encode($dados["produto"]); ?>" required>
			<br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="tipo_avaliacao">Tipo de avaliação sensorial: </label>
				<select type="text" id="tipo_avaliacao" name="tipo_avaliacao">
					<option value="cata" <?php if($dados["tipo_avaliacao"] == "cata") { ?> selected <?php } ?>>CATA</option>
					<option value="hedonica" <?php if($dados["tipo_avaliacao"] == "hedonica") { ?> selected <?php } ?>>Escala hedônica</option>
					<option value="pdq" <?php if($dados["tipo_avaliacao"] == "pdq") { ?> selected <?php } ?>>Painel descritivo quantitativo</option>
					<option value="triangular" <?php if($dados["tipo_avaliacao"] == "triangular") { ?> selected <?php } ?>>Teste triangular</option>
				</select>
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
			</div><br><br>

			<div>
				<label for="produto">Url de uma imagem do produto: </label>
				<input type="url" id="url_imagem" name="url_imagem" value="<?php echo utf8_encode($dados["url_imagem"]); ?>" style="width: 440px;">
			<br>
			</div>

			<div>
				<label for="nome_form">Título do formulário: </label>
				<input type="text" id="nome_form" name="nome_form" value="<?php echo utf8_encode($dados["nome_form"]); ?>"><br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="escala_min">Escala mínima: </label>
				<input type="number" id="escala_min" name="escala_min" value="<?php echo $dados["escala_min"]; ?>" style="width: 80px;">
			</div>

			<div>
				<label for="escala_max">Escala máxima: </label>
				<input type="number" id="escala_max" name="escala_max" value="<?php echo $dados["escala_max"]; ?>" style="width: 80px;">
			</div><br>

			<div style="float: left; margin-right: 20px;">
				<input type="checkbox" id="form_ativo" name="form_ativo" <?php if ($dados["form_ativo"] == 1) { ?> 
				checked <?php } ?> style="float: left; width: 5px">
				<label for="form_ativo" style="width: 210px">Habilitar formulário de avaliação</label>
			</div>

			<div>
				<input type="checkbox" id="consumo_ativo" name="consumo_ativo" <?php if ($dados["consumo_ativo"] == 1) { ?> 
				checked <?php } ?> style="float: left; width: 5px">
				<label for="consumo_ativo">Habilitar formulário de consumo</label>
			</div><br><br>

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