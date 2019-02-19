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

	// Abrir consulta ao banco de dados para pegar informações da empresa selecionada
	if (isset($_GET["codigo"])) {
		$empresa_id = $_GET["codigo"];
	} else {
		$empresa_id = 0;
	}

	$consulta = "SELECT * FROM empresas WHERE empresa_id = {$empresa_id}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["cnpj"])) {
		$nome_fantasia = utf8_decode($_POST["nome_fantasia"]);
		$relacao = utf8_decode($_POST["relacao"]);
		$razao_social = utf8_decode($_POST["razao_social"]);
		$cnpj = utf8_decode($_POST["cnpj"]);

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE empresas SET nome_fantasia = '{$nome_fantasia}', relacao = '{$relacao}', razao_social = '{$razao_social}', cnpj = '{$cnpj}' WHERE empresa_id = {$empresa_id}";

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

			// Verificar existência do cpf na base ----------------------------------

			$consulta_usuario = "SELECT * FROM empresas WHERE nome_fantasia = " . $nome_fantasia;

			$acesso = mysqli_query($conecta, $consulta_usuario);
			$existe_empresa = mysqli_fetch_assoc($acesso);

			if (!empty($existe_usuario)) { ?>
				<p>Essa empresa já foi cadastrada</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO empresas (nome_fantasia, relacao, razao_social, cnpj) VALUES ('$nome_fantasia', '$relacao', '$razao_social', '$cnpj')";

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
				
			$excluir = "DELETE FROM empresas WHERE empresa_id = {$empresa_id}";

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

	<style type="text/css">
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco"><?php 
				if ($acao == "alteracao") echo "ALTERAÇÃO DE EMPRESA";
				if ($acao == "exclusao") echo "EXCLUSÃO DE EMPRESA"; 
				?>CADASTRO DE EMPRESA</h2><br>

			<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $empresa_id; ?>" method="post">
				<div style="float: left; margin-right: 30px; width: 300px">
					<label for="nome_fantasia">Nome: </label>
					<input type="text" id="nome_fantasia" name="nome_fantasia" value="<?php echo utf8_encode($dados["nome_fantasia"]) ?>" style="width: 280px;">
				</div>

				<div>
					<label for="relacao">Relação: </label>
					<select id="relacao" name="relacao"><br>
						<option value="Cliente" <?php if($dados["relacao"] == "Cliente") { ?> selected <?php } ?>>Cliente</option>
						<option value="Fornecedor" <?php if($dados["relacao"] == "Fornecedor") { ?> selected <?php } ?>>Fornecedor</option>
					</select><br>
				</div>

				<div style="float: left; margin-right: 30px; width: 300px">
					<label for="razao_social">Razão social: </label>
					<input type="text" id="razao_social" name="razao_social" value="<?php echo utf8_encode($dados["razao_social"]) ?>" style="width: 280px;">
				</div>

				<div>
					<label for="cnpj">CNPJ: </label>
					<input type="text" id="cnpj" name="cnpj" value="<?php echo $dados["cnpj"] ?>">
				</div><br><br>

				<div>
					<input type="submit" id="botao" value="<?php 
						if ($acao == "alteracao") echo "Alterar cadastro";
						elseif ($acao == "exclusao") echo "Excluir cadastro";
						else echo "Cadastrar";
					?>" style="margin-left: 10px"><br>
					<br>
				</div>
			</form>
			<br><br>
		</article>

		<div class="direita">
			<a href="dados.php">Voltar</a><br><br>
		</div>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
