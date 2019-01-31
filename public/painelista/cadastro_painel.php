<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();

	if(isset($_SESSION["usuario"])) {

		if($_SESSION["funcao"] == "Painelista" || $_SESSION["funcao"] == "Candidato") {
			$consulta = "SELECT * FROM painelistas WHERE user_id = '{$_SESSION["user_id"]}'";
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);	

			if (!empty($dados)) {
				header("location:../principal.php");
			}
		}
	} else {
			header("location:<?php echo($caminho); ?>login.php");
		}


	if (isset($_POST["rg"])) {
		$fumante = isset($_POST["fumante"]) ? 1 : 0;

		$inserir = "INSERT INTO painelistas (user_id, rg, orgao_emissor, endereco, cidade, estado, intolerancia, fumante) VALUES ({$_SESSION["user_id"]}, '{$_POST["rg"]}', '{$_POST["orgao_emissor"]}', '{$_POST["endereco"]}', '{$_POST["cidade"]}', '{$_POST["estado"]}', '{$_POST["intolerancia"]}', {$fumante})";

		$operacao_inserir = mysqli_query($conecta, $inserir);

		if (!$operacao_inserir) {
			die("Falha na insercao ao banco.");
		}
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Cadastro Painelista</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo $caminho; ?>_css/estilo.css">

	<style>
		.folha_cadastro {
		  margin-bottom: 1px;
		  padding: 5px 15px;
		  z-index: -1;
		}

	</style>

</head>
<body>
	<main>
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
			<img src="http://aboutsolution.com.br/novo/wp-content/uploads/2017/12/Logo_About-Solution.png" width="210" height="70"
				title="logo About Solution">
			</a>
		</header>
		<h2 class="espaco">CADASTRO PARA PAINEL TREINADO</h2>
		<br>

		
		<form action="cadastro_painel.php" method="post">

			<!-- CADASTRO -->
			<div class="folha_cadastro">
				<label for="rg">R.G.: </label>
				<input type="text" id="rg" name="rg" placeholder="Insira seu R.G. (somente números)" size="30" required>

				<label for="orgao_emissor">Órgão emissor: </label>
				<input type="text" id="orgao_emissor" name="orgao_emissor" size="10"><br>

				<label for="endereco">Endereço: </label>
				<input type="text" id="endereco" name="endereco" size="60"><br>

				<label for="cidade">Cidade: </label>
				<input type="text" id="cidade" name="cidade" size="27">

				<label for="estado">Estado: </label>
				<input type="text" id="estado" name="estado" size="7"><br>

				<p>Apresenta algum tipo de intolerância?</p>
				<label for="intolerancia">Favor detalhar: </label>
				<input type="text" id="intolerancia" name="intolerancia" style="width:330px; height: 40px;"><br>

				<label for="fumante">É fumante? </label>
				<input type="checkbox" id="fumante" name="fumante"><br>
			</div>
			<br>

			<input type="submit" id="botao" value="Cadastrar dados"><br>
		</form>
		<br>


		<br>
		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
