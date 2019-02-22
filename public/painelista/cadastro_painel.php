<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();

	if(isset($_SESSION["usuario"])) {

		if($_SESSION["funcao"] == "Painelista" || $_SESSION["funcao"] == "Candidato") {
			$consulta = "SELECT * FROM usuarios WHERE user_id = '{$_SESSION["user_id"]}'";
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);	

			if (!empty($dados["rg"])) {
				header("location:{$caminho}public/principal.php");
			}
		}
	} else {
			header("location:{$caminho}login.php");
		}


	if (isset($_POST["rg"])) {
		$concordancia = isset($_POST["concordancia"]) ? 1 : 0;
		$rg = $_POST["rg"];
		$orgao_emissor = utf8_decode($_POST["orgao_emissor"]);
		$cep = utf8_decode($_POST["cep"]);
		$rua = utf8_decode($_POST["rua"]);
		$numero_casa = utf8_decode($_POST["numero"]);
		$complemento = utf8_decode($_POST["complemento"]);
		$bairro = utf8_decode($_POST["bairro"]);
		$cidade = utf8_decode($_POST["cidade"]);
		$estado = utf8_decode($_POST["estado"]);
		$intolerancia = utf8_decode($_POST["intolerancia"]);
		$fumante = $_POST["fumante"];


		$inserir = "UPDATE usuarios SET rg = '{$rg}', orgao_emissor = '{$orgao_emissor}', cep = '{$cep}', rua = '{$rua}', numero_casa = '{$numero_casa}', complemento = '{$complemento}', bairro = '{$bairro}', cidade = '{$cidade}', estado = '{$estado}', intolerancia = '{$intolerancia}', fumante = {$fumante} WHERE user_id = {$_SESSION["user_id"]}";

		$operacao_inserir = mysqli_query($conecta, $inserir);

		if (!$operacao_inserir) {
			echo $inserir;
			die("Falha na insercao ao banco.");
		}

		header("location:{$caminho}public/principal.php");
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

	<script type='text/javascript' src='http://files.rafaelwendel.com/jquery.js'></script>
	<script type='text/javascript' src='cep.js'></script>

</head>
<body>
	<main>
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
			<img src="http://aboutsolution.com.br/novo/wp-content/uploads/2017/12/Logo_About-Solution.png" width="210" height="70"
				title="logo About Solution">
			</a>
		</header>

		<article style="margin-left: 10px">
		<h2 class="espaco">CADASTRO PARA PAINELISTAS</h2>
		<br>

		
		<form action="cadastro_painel.php" method="post">

			<!-- CADASTRO -->
			<div style="float: left; margin-right: 30px;">
				<label for="rg">R.G.: </label>
				<input type="text" id="rg" name="rg" placeholder="Insira seu R.G. (somente números)" required>
			</div>

			<div>
				<label for="orgao_emissor">Órgão emissor: </label>
				<input type="text" id="orgao_emissor" name="orgao_emissor" required placeholder="Ex.: SSP-PR"><br>
			</div><br>

			<div style="float: left; margin-right: 30px;">
				<label for="cep">CEP: </label>
				<input type="text" id="cep" name="cep" placeholder="CEP (Somente números)" required style="width: 100px"><br>
			</div>

			<div>
				<label for="rua">Rua: </label>
				<input type="text" id="rua" name="rua" placeholder="Ex.: Rua das Flores" style="width: 300px"><br>
			</div>

			<div style="float: left; margin-right: 10px;">
				<label for="numero">Número: </label>
				<input type="text" id="numero" name="numero" style="width: 80px"><br>
			</div>

			<div style="float: left; margin-right: 30px;">
				<label for="complemento">Complemento: </label>
				<input type="text" id="complemento" name="complemento" style="width: 80px"><br>
			</div>

			<div>
				<label for="bairro">Bairro: </label>
				<input type="text" id="bairro" name="bairro"><br>
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
				<label for="fumante">É fumante? </label>
				<select id="fumante" name="fumante" style="width: 80px"><br>
					<option value=0 selected>Não</option>
					<option value=1>Sim</option>
				</select>
			</div>
			<br>

			<div>
				<input type="checkbox" name="concordancia" id="concordancia" style="float: left; width: 15px" required>
				<p style="font-size: 90%; width: 440px; line-height: 20px">Declaro que concordo com os termos de prestação de serviço à About Solution</p>
			</div><br>

			<input type="submit" id="botao" value="Cadastrar dados"><br>
		</form>
		<br>
		</article>


		<br>
		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
if (isset($operacao_inserir)) {
	mysqli_free_result($operacao_inserir);
}
	mysqli_close($conecta);
?>
