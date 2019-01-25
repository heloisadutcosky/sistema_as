<?php 
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once("_incluir/verificacao_acesso.php");

	//Estabelecer conexão com base de dados
	require_once("../conexao/conexao.php"); 

	// Projeto selecionado
	$projeto_id = isset($_POST["codigo"]) ? $_POST["codigo"] : 0

	// Inputs
	$sessao = isset($_POST["sessao"]) ? $_POST["sessao"] : isset($_GET["sessao"]) ? $_GET["sessao"] : "";

	// Cadastrar
	$consulta = "SELECT * FROM " . $tabelas;
	$acesso = mysqli_query($conecta, $consulta);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="../public/_css/estilo.css">

</head>
<body>
	<main>
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
				<img src="http://aboutsolution.com.br/novo/wp-content/uploads/2017/12/Logo_About-Solution.png" width="210" height="70"
				title="logo About Solution">
			</a>
			<h2 class="espaco"></h2>
		</header>
		<br>
			<h4>Cadastro de sessão</h4>
			<p>Qual vai ser o número da sessão e em que data ela será realizada?</p>
			<form action="cadastrar_sessao.php?sessao=<?php echo($sessao); ?>&data=<?php echo($data); ?>" method="post">
					<label for="sessao">Sessão: </label>
					<input type="number" id="sessao" name="sessao" value="$sessao" required><br>

					<label for="data">Data: </label>
					<input type="date" id="data" name="data" value="$data" required><br>
			

			<p>Favor cadastrar as amostras e os códigos que serão utilizados</p>
			
				<label for="amostra_descricao">Descrição: </label>
				<input type="text" id="amostra_descricao" name="amostra_descricao" value="" required><br>

				<label for="data">Código a ser utilizado nessa sessão:</label>
				<input type="text" id="data" name="data" value="" required><br>

				<input type="submit" id="botao" value="Cadastrar"><br>
			</form>

			<br>
			<br>
			<?php include_once("../public/_incluir/rodape.php"); ?>

		</main>
	</body>
	</html>
