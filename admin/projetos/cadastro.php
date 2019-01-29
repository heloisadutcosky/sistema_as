<?php

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	// Inputs

	if (isset($_POST["empresa"])) {
		$empresa = $_POST["empresa"];
		$produto = $_POST["produto"];
		$descricao_projeto = $_POST["descricao_projeto"];
		$tipo_avaliacao = $_POST["tipo_avaliacao"];
		$escala_min = $_POST["escala_min"];
		$escala_max = $_POST["escala_max"];
		$data_inicio = $_POST["data_inicio"];
		$data_fim = $_POST["data_fim"];

		$consulta = "SELECT * FROM projetos WHERE empresa = " . $empresa . " AND produto = " . $produto . " AND tipo_avaliacao = " . $tipo_avaliacao;

		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);
	} else {
		$empresa = "";
		$produto = "";
		$descricao_projeto = "";
		$tipo_avaliacao = "";
		$escala_min = "";
		$escala_max = "";
		$data_inicio = "";
		$data_fim = "";
	}

	// Ver se sessao ja foi cadastrada
	if (empty($dados)) {  
		if (isset($_POST["empresa"])) {
			$inserir = "INSERT INTO projetos (empresa, produto, descricao_projeto, tipo_avaliacao, escala_min, escala_max, data_inicio, data_fim) VALUES ('$empresa', '$produto', '$descricao_projeto', '$tipo_avaliacao', $escala_min, $escala_max, '$data_inicio', '$data_fim')";
			$operacao_inserir = mysqli_query($conecta, $inserir); 
		}
?>

		<!DOCTYPE html>
		<html lang="pt-BR">
		<head>
		<title>Cadastro Projeto</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

		</head>
		<body>
			<main>
				<?php include_once($caminho . "_incluir/topo.php"); ?>
				<h2 class="espaco">CADASTRO PROJETO</h2>

				<form action="cadastro.php" method="post">
					<label for="empresa">Empresa: </label>
					<input type="text" id="empresa" name="empresa" value="<?php echo $empresa; ?>" required><br>

					<label for="produto">Produto: </label>
					<input type="text" id="produto" name="produto" value="<?php echo $produto; ?>" required><br>

					<label for="tipo_avaliacao">Tipo de avaliação sensorial: </label>
					<input type="text" id="tipo_avaliacao" name="tipo_avaliacao" value="<?php echo $tipo_avaliacao; ?>" required><br>

					<label for="escala_min">Escala mínima: </label>
					<input type="number" id="escala_min" name="escala_min" value="<?php echo $escala_min; ?>" required><br>

					<label for="escala_max">Escala máxima: </label>
					<input type="number" id="escala_max" name="escala_max" value="<?php echo $escala_max; ?>" required><br>

					<label for="descricao_projeto">Descrição do projeto: </label>
					<input type="text" id="descricao_projeto" name="descricao_projeto" value="<?php echo $descricao_projeto; ?>" required><br>

					<label for="data_inicio">Data de início: </label>
					<input type="date" id="data_inicio" name="data_inicio" value="<?php echo $data_inicio; ?>">

					<label for="data_fim">Data de fim: </label>
					<input type="date" id="data_fim" name="data_fim" value="<?php echo $data_fim; ?>"><br>

					<input type="submit" id="botao" value="Cadastrar"><br>
				</form>

				<br>
				<br>
				<?php include_once($caminho . "_incluir/rodape.php"); ?>

			</main>
		</body>
		</html>

<?php }
	else { ?>
		<p>Esse projeto já foi cadastrado</p>
	<?php } ?>
