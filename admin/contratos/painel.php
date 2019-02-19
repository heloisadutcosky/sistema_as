<?php

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$n_opcoes = isset($_POST["n_opcoes"]) ? $_POST["n_opcoes"] : 0;
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";

	// Abrir consulta ao banco de dados para pegar informações da empresa selecionada
	if (isset($_GET["codigo"])) {
		$contrato_id = $_GET["codigo"];
	} else {
		$contrato_id = 0;
	}
	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["data_inicio"])) {
		$empresa_id = utf8_decode($_POST["empresa_id"]);
		$descricao = utf8_decode($_POST["descricao"]);
		$data_inicio = utf8_decode($_POST["data_inicio"]);
		$data_fim = utf8_decode($_POST["data_fim"]);

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE contratos SET empresa_id = {$empresa_id}, descricao = '{$descricao}', data_inicio = '{$data_inicio}', data_fim = '{$data_fim}' WHERE contrato_id = {$contrato_id}";

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

			$consulta_usuario = "SELECT * FROM contratos WHERE empresa_id = {$empresa_id} AND data_inicio = '{$data_inicio}'";

			$acesso = mysqli_query($conecta, $consulta_usuario);
			$existe_empresa = mysqli_fetch_assoc($acesso);

			if (!empty($existe_usuario)) { ?>
				<p>Esse contrato já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO contratos (empresa_id, descricao, data_inicio, data_fim) VALUES ('$empresa_id', '$descricao', '$data_inicio', '$data_fim')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro dos dados.");
				} 
			}
		}
		// --------------------------------------------------------------------------
	}
	// ------------------------------------------------------------------------------
	$consulta = "SELECT * FROM contratos WHERE contrato_id = {$contrato_id}";
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
				if ($acao == "alteracao") {echo "ALTERAÇÃO DE CONTRATO";}
				else if ($acao == "exclusao") {echo "EXCLUSÃO DE CONTRATO";} 
				else {echo "CADASTRO DE CONTRATO";} 
				?></h2><br>

			<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $contrato_id; ?>" method="post">
				<div style="float: left; margin-right: 52px; margin-top: 18px; margin-left: 3px">
					<label for="empresa_id">Empresa: </label>
					<select id="empresa_id" name="empresa_id" style="width: 250px;">
					<br>
						<?php 
						$consulta_usuario = "SELECT * FROM empresas";
						$acesso = mysqli_query($conecta, $consulta_usuario);
						while ($linha = mysqli_fetch_assoc($acesso)) { 
						?>
						<option value="<?php echo $linha["empresa_id"]; ?>" 
						<?php 
							if($dados["empresa_id"] == $linha["empresa_id"]) { ?> 
								selected 
						<?php } ?>>
						<?php echo $linha["nome_fantasia"]; ?></option>
						<?php } ?>
					</select>
				</div><br>

				<div style="float: left; margin-right: 10px;">
					<label for="data_inicio">Data Início: </label>
					<input type="date" id="data_inicio" name="data_inicio" value="<?php echo $dados["data_inicio"] ?>" style="width: 100px;" required>
				</div>

				<div>
					<label for="data_fim">Data Fim: </label>
					<input type="date" id="data_fim" name="data_fim" value="<?php echo $dados["data_fim"] ?>" style="width: 100px;">
				</div><br>

				<div>
					<label for="descricao">Descrição do contrato: </label>
					<input type="text" id="descricao" name="descricao" value="<?php echo utf8_encode($dados["descricao"]) ?>" style="width: 550px; height: 40px">
				</div><br><br>

				<div style="width: 550px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; margin-left: 10px">
					<div style="margin-right: 10px;">
						<?php 
						foreach (range(0, $n_opcoes) as $i) { ?>
								<br>
								<input type="number" id="vezes" name="vezes<?php echo($i); ?>"
								<?php 
								if(isset($_POST["vezes{$i}"])) { ?>
									value="<?php echo($_POST["vezes{$i}"]); ?>"
								<?php } ?>
								style="float: left; width: 30px; margin-right: 5px; margin-top: -5px">

								<p style="float: left; margin-right: -5px; margin-top: -5px;"> pagamento(s) de R$</p>

								<input type="number" id="valor" name="valor<?php echo($i); ?>"
								<?php 
								if(isset($_POST["valor{$i}"])) { ?>
									value="<?php echo($_POST["valor{$i}"]); ?>"
								<?php } ?>
								required style="float: left; width: 100px; margin-right: 10px; margin-top: -5px">

								<p style="float: left; margin-right: 0px; margin-top: -5px;"> em</p>
								
								<input type="date" id="data_pagamento" name="data_pagamento<?php echo($i); ?>"
								<?php 
								if(isset($_POST["data_pagamento{$i}"])) { ?>
									value="<?php echo($_POST["data_pagamento{$i}"]); ?>"
								<?php } ?>
								required style="float: left; width: 100px; margin-right: 10px; margin-top: -5px"><br>
								
						<?php } ?>
					</div><br>
					<input type="hidden" name="n_opcoes" value="<?php echo ($n_opcoes + 1); ?>">
					<button type="submit" value="+" style="margin-right: 20px; float: right; margin-top: -40px;"><b style="font-size: 125%;">+</b></button>
				</div><br><br>

				<div>
					<input type="submit" id="botao" value="<?php 
						if ($acao == "alteracao") echo "Alterar cadastro";
						elseif ($acao == "exclusao") echo "Excluir cadastro";
						else echo "Cadastrar";
					?>" style="margin-left: 10px">
				</div><br>
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
