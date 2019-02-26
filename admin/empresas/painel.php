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
		$cep = utf8_decode($_POST["cep"]);
		$logradouro = utf8_decode($_POST["logradouro"]);
		$numero_end = utf8_decode($_POST["numero"]);
		$complemento = utf8_decode($_POST["complemento"]);
		$bairro = utf8_decode($_POST["bairro"]);
		$cidade = utf8_decode($_POST["cidade"]);
		$estado = utf8_decode($_POST["estado"]);

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE empresas SET nome_fantasia = '{$nome_fantasia}', relacao = '{$relacao}', razao_social = '{$razao_social}', cnpj = '{$cnpj}', cep = '{$cep}', logradouro = '{$logradouro}', numero_end = '{$numero_end}', complemento = '{$complemento}', bairro = '{$bairro}', cidade = '{$cidade}', estado = '{$estado}' WHERE empresa_id = {$empresa_id}";

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
				$cadastrar = "INSERT INTO empresas (nome_fantasia, relacao, razao_social, cnpj, cep, logradouro, numero_end, complemento, bairro, cidade, estado) VALUES ('$nome_fantasia', '$relacao', '$razao_social', '$cnpj', '$cep', '$logradouro', '$numero_end', '$complemento', '$bairro', '$cidade', '$estado')";

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
	<title>Alteração de empresa</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

	<style type="text/css">
	</style>

	<script type='text/javascript' src='http://files.rafaelwendel.com/jquery.js'></script>
	<script type='text/javascript' src='<?php echo($caminho); ?>_js/cnpj.js'></script>

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
				<div style="float: left; margin-right: 30px;">
					<label for="nome_fantasia">Nome: </label>
					<input type="text" id="nome_fantasia" name="nome_fantasia" value="<?php echo utf8_encode($dados["nome_fantasia"]) ?>" style="width: 300px;">
				</div>

				<div>
					<label for="relacao">Relação: </label>
					<select id="relacao" name="relacao" style="width: 200px;"><br>
						<option value="Cliente" <?php if($dados["relacao"] == "Cliente") { ?> selected <?php } ?>>Cliente</option>
						<option value="Fornecedor" <?php if($dados["relacao"] == "Fornecedor") { ?> selected <?php } ?>>Fornecedor</option>
					</select>
				</div><br>

				<div style="float: left; margin-right: 30px">
					<label for="cnpj">CNPJ: </label>
					<input type="text" id="cnpj" name="cnpj" value="<?php echo $dados["cnpj"] ?>" style="width: 150px;">
				</div>

				<div>
					<label for="razao_social">Razão social: </label>
					<input type="text" id="nome" name="razao_social" value="<?php echo utf8_encode($dados["razao_social"]) ?>" style="width: 345px;">
				</div><br>

				<div style="float: left; margin-right: 30px;">
					<label for="cep">CEP: </label>
					<input type="text" id="cep" name="cep" value="<?php echo $dados["cep"] ?>" style="width: 150px"><br>
				</div>

				<div>
					<label for="logradouro">Logradouro: </label>
					<input type="text" id="logradouro" name="logradouro" value="<?php echo utf8_encode($dados["logradouro"]) ?>" style="width: 345px"><br>
				</div>

				<div style="float: left; margin-right: 20px;">
					<label for="numero">Número: </label>
					<input type="text" id="numero" name="numero" value="<?php echo $dados["numero_end"] ?>" style="width: 58px"><br>
				</div>


				<div style="float: left; margin-right: 30px;">
					<label for="bairro">Bairro: </label>
					<input type="text" id="bairro" name="bairro" value="<?php echo utf8_encode($dados["bairro"]) ?>" style="width: 310px">
				</div>

				<div>
					<label for="complemento">Complemento: </label>
					<input type="text" id="complemento" name="complemento" value="<?php echo $dados["complemento"] ?>" style="width: 80px">
				</div><br>

				<div style="float: left; margin-right: 30px;">
					<label for="cidade">Cidade: </label>
					<input type="text" id="cidade" name="cidade" value="<?php echo utf8_encode($dados["cidade"]) ?>" style="width: 415px">
				</div>

				<div>
					<label for="estado">Estado: </label>
					<input type="text" id="estado" name="estado" value="<?php echo $dados["estado"] ?>" style="width: 80px"><br>
				</div><br>

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

if (isset($operacao_cadastrar)) {
	mysqli_free_result($operacao_cadastrar);
}
if (isset($operacao_alterar)) {
	mysqli_free_result($operacao_alterar);
}
if (isset($operacao_excluir)) {
	mysqli_free_result($operacao_excluir);
}
	// Fechar conexão
	mysqli_close($conecta);
?>
