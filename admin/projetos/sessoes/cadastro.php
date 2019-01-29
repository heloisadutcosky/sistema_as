<?php 
	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	// Projeto selecionado
	$projeto_id = isset($_GET["codigo"]) ? $_GET["codigo"] : 0; 

	// Inputs
	if (isset($_POST["sessao"])) {
		$sessao = $_POST["sessao"];
		$data = $_POST["data"];
		$amostra_descricao = $_POST["amostra_descricao"];
		$amostra_codigo = $_POST["amostra_codigo"];

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $projeto_id . " AND sessao = " . $sessao;

		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);
	} else {
		$sessao = "";
		$data = "";
	}

	// Ver se sessao ja foi cadastrada
	if (empty($dados)) {  
		if (isset($_POST["sessao"])) {
			$inserir = "INSERT INTO amostras (projeto_id, sessao, data, amostra_descricao, amostra_codigo) VALUES ($projeto_id, $sessao, '$data', '$amostra_descricao', '$amostra_codigo')";
			$operacao_inserir = mysqli_query($conecta, $inserir); 
		}
?>

		<!DOCTYPE html>
		<html lang="pt-BR">
		<head>
		<title>Cadastro Sessão</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

		</head>
		<body>
		<main>
			<?php include_once($caminho . "_incluir/topo.php"); ?>
			<h2 class="espaco">PROJETO <?php echo $projeto_id; ?></h2>
			
				<h4>Cadastro da sessão e das amostras</h4><br>
				<p>Qual vai ser o número da sessão e em que data ela será realizada?</p>
				<form action="cadastro.php?codigo=<?php echo($projeto_id); ?>" method="post">
						<label for="sessao">Sessão: </label>
						<input type="number" id="sessao" name="sessao" value="<?php echo $sessao; ?>" required><br>

						<label for="data">Data: </label>
						<input type="date" id="data" name="data" value="<?php echo $data; ?>" required><br>
						
						<p>Favor cadastrar as amostras e os códigos que serão utilizados</p>
						
						<label for="amostra_descricao">Descrição: </label>
						<input type="text" id="amostra_descricao" name="amostra_descricao" value="" required><br>

						<label for="amostra_codigo">Código: </label>
						<input type="text" id="amostra_codigo" name="amostra_codigo" value="" required><br>

						<input type="submit" id="botao" value="Cadastrar">
				</form>

				<div class="direita">
					<a href="../dados.php">Voltar</a><br><br>
				</div>

				<br>
				<br>
				<?php include_once($caminho . "_incluir/rodape.php"); ?>

			</main>
		</body>
		</html>

<?php }
	else { ?>
		<p>Essa sessão já foi cadastrada</p>
	<?php } ?>
