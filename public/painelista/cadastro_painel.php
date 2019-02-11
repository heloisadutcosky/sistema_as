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
				header("location:{$caminho}public/principal.php");
			}
		}
	} else {
			header("location:{$caminho}login.php");
		}


	if (isset($_POST["rg"])) {
		$concordancia = isset($_POST["concordancia"]) ? 1 : 0;

		$inserir = "INSERT INTO painelistas (user_id, rg, orgao_emissor, endereco, cidade, estado, intolerancia, fumante) VALUES ({$_SESSION["user_id"]}, '{$_POST["rg"]}', '{$_POST["orgao_emissor"]}', '{$_POST["endereco"]}', '{$_POST["cidade"]}', '{$_POST["estado"]}', '{$_POST["intolerancia"]}', {$_POST["fumante"]})";

		$operacao_inserir = mysqli_query($conecta, $inserir);

		if (!$operacao_inserir) {
			die("Falha na insercao ao banco.");
		}

		header("location:<?php echo($caminho); ?>public/principal.php");
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Cadastro Painelista</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo $caminho; ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

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
			<div style="float: left; margin-right: 30px;">
				<label for="rg">R.G.: </label>
				<input type="text" id="rg" name="rg" placeholder="Insira seu R.G. (somente números)" required>
			</div>

			<div>
				<label for="orgao_emissor">Órgão emissor: </label>
				<input type="text" id="orgao_emissor" name="orgao_emissor"><br>
			</div><br>

			<div>
				<label for="cep">CEP: </label>
				<input type="text" id="cep" name="cep" placeholder="XXXXX-XXX" required><br>
			</div>

			<div>
				<label for="endereco">Endereço: </label>
				<input type="text" id="endereco" name="endereco" style="width: 440px"><br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="cidade">Cidade: </label>
				<input type="text" id="cidade" name="cidade" style="width: 310px">
			</div>

			<div>
				<label for="estado">Estado: </label>
				<input type="text" id="estado" name="estado" style="width: 80px"><br>
			</div><br>

			<div>
				<label for="intolerancia">Apresenta algum tipo de intolerância? Favor detalhar: </label>
				<input type="text" id="intolerancia" name="intolerancia" style="width:440px; height: 40px;"><br>
			</div>

			<div>
				<label for="fumante">Fumante? </label>
				<select id="fumante" name="fumante" style="width: 80px"><br>
					<option value=0 selected>Não</option>
					<option value=1>Sim</option>
				</select>
			</div>
			<br>

			<div>
				<input type="checkbox" name="concordancia" id="concordancia" style="float: left; width: 20px" required>
				<p>Declaro que blablabla</p>
			</div><br><br>

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
