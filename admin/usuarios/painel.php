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
		$user_id = $_GET["codigo"];
		$_SESSION["user_id"] = $user_id;
	} else {
		$user_id = 0;
	}

	$consulta = "SELECT * FROM usuarios WHERE user_id = {$user_id}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["cpf"])) {
		$user_id = $_SESSION["user_id"];
		$cpf = $_POST["cpf"];
		$nome = utf8_decode($_POST["nome"]);
		$sexo = $_POST["sexo"];
		$nascimento = $_POST["nascimento"];
		$escolaridade = utf8_decode($_POST["escolaridade"]);
		$email = $_POST["email"];
		$telefone = $_POST["telefone"];
		$funcao = $_POST["funcao"];
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

		$words = explode(" ", $nome);
		$iniciais = "";

		foreach ($words as $w) {
  			$iniciais .= $w[0];
		}

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE usuarios SET cpf = '{$cpf}', nome = '{$nome}', sexo = '{$sexo}', nascimento = '{$nascimento}', escolaridade = '{$escolaridade}', email = '{$email}', telefone = '{$telefone}', funcao = '{$funcao}', iniciais = '{$iniciais}', rg = '{$rg}', orgao_emissor = '{$orgao_emissor}', cep = '{$cep}', rua = '{$rua}', numero_casa = '{$numero_casa}', complemento = '{$complemento}', bairro = '{$bairro}', cidade = '{$cidade}', estado = '{$estado}', intolerancia = '{$intolerancia}', fumante = {$fumante} WHERE user_id = {$_SESSION["user_id"]}";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				echo $alterar;
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php");
			}
		}
		// --------------------------------------------------------------------------

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do cpf na base ----------------------------------

			$consulta_usuario = "SELECT * FROM usuarios WHERE cpf = " . $cpf;

			$acesso = mysqli_query($conecta, $consulta_usuario);
			$existe_usuario = mysqli_fetch_assoc($acesso);

			if (!empty($existe_usuario)) { ?>
				<p>Já existe um cadastro com esse cpf</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO usuarios (cpf, nome, sexo, nascimento, escolaridade, email, telefone, funcao, iniciais, rg, orgao_emissor, cep, rua, numero_casa, complemento, bairro, cidade, estado, intolerancia, fumante) VALUES ('$cpf', '$nome', '$sexo', '$nascimento', '$escolaridade', '$email', '$telefone', '$funcao', '$iniciais', '$rg', '$orgao_emissor', '$cep', '$rua', '$numero_casa', '$complemento', '$bairro', '$cidade', '$estado', '$intolerancia', '$fumante')";

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
				
			$excluir = "DELETE FROM usuarios WHERE user_id = {$_SESSION["user_id"]}";

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

	<script type='text/javascript' src='http://files.rafaelwendel.com/jquery.js'></script>
	<script type='text/javascript' src='<?php echo($caminho); ?>_js/cep.js'></script>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco"><?php 
				if ($acao == "alteracao") echo "ALTERAÇÃO DE ";
				if ($acao == "exclusao") echo "EXCLUSÃO DE "; 
				?>CADASTRO DE USUÁRIO</h2><br>

			<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $user_id; ?>" method="post">
				
				<p style="margin-left: 10px">Cadastro geral: </p>

				
				<div style="float: left; margin-right: 30px;">
					<label for="nome">Nome: </label>
					<input type="text" id="nome" name="nome" value="<?php echo utf8_encode($dados["nome"]) ?>">
				</div>

				<div>
					<label for="cpf">CPF: </label>
					<input type="text" id="cpf" name="cpf" value="<?php echo $dados["cpf"] ?>">
				</div><br>

				<div style="float: left; margin-right: 30px;">
					<label for="nascimento">Data de nascimento: </label>
					<input type="date" id="nascimento" name="nascimento" value="<?php echo $dados["nascimento"] ?>" required>
				</div>

				<div>
					<label for="sexo">Sexo: </label>			
					<select list="sexos" id="sexo" name="sexo" selected="<?php echo $dados["sexo"] ?>">
						<?php if($dados["sexo"] == "Feminino") { ?>
							<option value="Feminino" selected>Feminino</option>
							<option value="Masculino">Masculino</option>
						<?php } else { ?>
							<option value="Feminino">Feminino</option>
							<option value="Masculino" selected>Masculino</option>
						<?php } ?>
					</select>
				</div><br>

				<div>
					<label for="escolaridade">Escolaridade: </label>
					<select id="escolaridade" name="escolaridade"><br>
						<?php switch ($dados["escolaridade"]) {

							case 'Ensino Médio': ?>
								<option value="Ensino Fundamental">Ensino Fundamental</option>
								<option value="Ensino Médio" selected>Ensino Médio</option>
								<option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
								<option value="Ensino Superior Completo">Ensino Superior Completo</option>
								<?php break;

							case 'Ensino Superior Incompleto': ?>
								<option value="Ensino Fundamental">Ensino Fundamental</option>
								<option value="Ensino Médio">Ensino Médio</option>
								<option value="Ensino Superior Incompleto" selected>Ensino Superior Incompleto</option>
								<option value="Ensino Superior Completo">Ensino Superior Completo</option>
								<?php break;

							case 'Ensino Superior Completo': ?>
								<option value="Ensino Fundamental">Ensino Fundamental</option>
								<option value="Ensino Médio">Ensino Médio</option>
								<option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
								<option value="Ensino Superior Completo" selected>Ensino Superior Completo</option>

								<?php break;
							default: ?>
								<option value="Ensino Fundamental" selected>Ensino Fundamental</option>
								<option value="Ensino Médio">Ensino Médio</option>
								<option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
								<option value="Ensino Superior Completo">Ensino Superior Completo</option>
								<?php break; 
							}?>
					</select>
				</div><br>

				<div style="float: left; margin-right: 30px;">
					<label for="email">E-mail: </label>
					<input type="email" id="email" name="email" value="<?php echo $dados["email"] ?>">
				</div>

				<div>
					<label for="telefone">Telefone: </label>
					<input type="tel" id="telefone" name="telefone" value="<?php echo $dados["telefone"] ?>">
				</div><br>

				<div>
					<label for="funcao">Tipo de usuário: </label>
					<select id="funcao" name="funcao"><br>
						<option value="Consumidor" <?php if($dados["funcao"] == "Consumidor") { ?> selected <?php } ?>>Consumidor</option>
						<option value="Painelista" <?php if($dados["funcao"] == "Painelista") { ?> selected <?php } ?>>Painelista</option>
						<option value="Candidato" <?php if($dados["funcao"] == "Candidato") { ?> selected <?php } ?>>Candidato</option>
						<option value="Administrador" <?php if($dados["funcao"] == "Administrador") { ?> selected <?php } ?>>Administrador</option>
						<option value="Administrador restrito" <?php if($dados["funcao"] == "Administrador restrito") { ?> selected <?php } ?>>Administrador restrito</option>
						<option value="Cliente" <?php if($dados["funcao"] == "Cliente") { ?> selected <?php } ?>>Cliente</option>
					</select><br><br>
				</div>

				<p style="margin-left: 10px">Informações para painelistas: </p>

				<div style="float: left; margin-right: 30px;">
					<label for="rg">R.G.: </label>
					<input type="text" id="rg" name="rg" value="<?php echo $dados["rg"] ?>">
				</div>

				<div>
					<label for="orgao_emissor">Órgão emissor: </label>
					<input type="text" id="orgao_emissor" name="orgao_emissor" value="<?php echo utf8_encode($dados["orgao_emissor"]) ?>"><br>
				</div><br>

				<div style="float: left; margin-right: 30px;">
					<label for="cep">CEP: </label>
					<input type="text" id="cep" name="cep" value="<?php echo $dados["cep"] ?>" style="width: 100px"><br>
				</div>

				<div>
					<label for="rua">Rua: </label>
					<input type="text" id="rua" name="rua" value="<?php echo utf8_encode($dados["rua"]) ?>" style="width: 300px"><br>
				</div>

				<div style="float: left; margin-right: 10px;">
					<label for="numero">Número: </label>
					<input type="text" id="numero" name="numero" value="<?php echo $dados["numero_casa"] ?>" style="width: 80px"><br>
				</div>

				<div style="float: left; margin-right: 30px;">
					<label for="complemento">Complemento: </label>
					<input type="text" id="complemento" name="complemento" value="<?php echo $dados["complemento"] ?>" style="width: 80px"><br>
				</div>

				<div>
					<label for="bairro">Bairro: </label>
					<input type="text" id="bairro" name="bairro" value="<?php echo utf8_encode($dados["bairro"]) ?>"><br>
				</div>

				<div style="float: left; margin-right: 30px;">
					<label for="cidade">Cidade: </label>
					<input type="text" id="cidade" name="cidade" value="<?php echo utf8_encode($dados["cidade"]) ?>" style="width: 310px">
				</div>

				<div>
					<label for="estado">Estado: </label>
					<input type="text" id="estado" name="estado" value="<?php echo $dados["estado"] ?>" style="width: 80px"><br>
				</div><br>

				<div>
					<label for="intolerancia">Apresenta algum tipo de intolerância? Favor detalhar: </label>
					<input type="text" id="intolerancia" name="intolerancia" value="<?php echo utf8_encode($dados["intolerancia"]) ?>" style="width:440px; height: 40px;"><br>
				</div>

				<div>
					<label for="fumante">É fumante? </label>
					<select id="fumante" name="fumante" value="<?php echo $dados["fumante"] ?>" style="width: 80px"><br>
						<option value=0 <?php if($dados["fumante"] == 0) { ?> selected <?php } ?>>Não</option>
						<option value=1 <?php if($dados["fumante"] == 1) { ?> selected <?php } ?>>Sim</option>
					</select>
				</div>
				<br>

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
