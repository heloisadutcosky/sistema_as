<?php require_once("../conexao/conexao.php"); ?>

<?php  
	// Iniciar sessão
	session_start();

	// Abrir consulta ao banco de dados
	if (isset($_GET["codigo"])) {
		$userID = $_GET["codigo"];
		$_SESSION["userID"] = $userID;
	} else {
		$userID = 1;
	}

	$consulta = "SELECT * FROM avaliadores WHERE userID = {$userID}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);


	if (isset($_POST["cpf"])) {
		$userID = $_SESSION["userID"];
		$cpf = $_POST["cpf"];
		$nome = utf8_decode($_POST["nome"]);
		$sexo = $_POST["sexo"];
		$nascimento = $_POST["nascimento"];
		$escolaridade = utf8_decode($_POST["escolaridade"]);
		$email = $_POST["email"];
		$telefone = $_POST["telefone"];
		$funcao = "Painelista";
	
		$alterar = "UPDATE avaliadores SET cpf = '{$cpf}', nome = '{$nome}', sexo = '{$sexo}', nascimento = '{$nascimento}', escolaridade = '{$escolaridade}', email = '{$email}', telefone = '{$telefone}', funcao = '{$funcao}' WHERE userID = {$_SESSION["userID"]}";

		$operacao_alterar = mysqli_query($conecta, $alterar);

		if (!$operacao_alterar) {
			die("Falha na alteração dos dados.");
		} else {
			header("location:avaliadores.php");
		}
	}

	// Liberar dados da memória
	mysqli_free_result($acesso);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Alteração de cadastro de avaliadores</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="../public/_css/estilo.css">

</head>
<body>
	<main>
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
				<img src="../public/imagens/logo.jpg" width="210" height="70"
				title="logo About Solution">
			</a>
			<h2 class="espaco">ALTERAÇÃO DE CADASTRO</h2>
		</header>

		<?php  
			if (isset($mensagem)) {
		?>
			<p class="errado"><?php echo $mensagem;  ?></p>
			<br>
			<a href="cadastro.php">Realizar login</a>
			<br>
			<br>
		<?php 
			}
		?>

		<br>
		<form action="alteracao.php" method="post">

			<label for="cpf">CPF</label>
			<input type="text" id="cpf" name="cpf" value="<?php echo $dados["cpf"] ?>"><br>

			<label for="nome">Nome</label>
			<input type="text" id="nome" name="nome" value="<?php echo $dados["nome"] ?>"><br>

			<label for="nascimento">Data de nascimento</label>
			<input type="date" id="nascimento" name="nascimento" value="<?php echo $dados["nascimento"] ?>"><br>

			<!--
			<label for="sexo">Sexo</label>			
			<select id="sexo" name="sexo" value="<?php echo $dados["sexo"] ?>">
				<option value="Feminino">Feminino</option>
				<option value="Masculino">Masculino</option>
			</select><br><br>
			-->

			<label for="sexo">Sexo</label>			
			<select list="sexos" id="sexo" name="sexo" selected="<?php echo $dados["sexo"] ?>">
				<?php if($dados["sexo"] == "Feminino") { ?>
					<option value="Feminino" selected>Feminino</option>
					<option value="Masculino">Masculino</option>
				<?php } else { ?>
					<option value="Feminino">Feminino</option>
					<option value="Masculino" selected>Masculino</option>
				<?php } ?>
			</select><br><br>

			<label for="escolaridade">Escolaridade</label>
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

			</select><br><br>

			<label for="email">E-mail</label>
			<input type="email" id="email" name="email" value="<?php echo $dados["email"] ?>"><br>

			<label for="telefone">Telefone</label>
			<input type="tel" id="telefone" name="telefone" value="<?php echo $dados["telefone"] ?>"><br>
			<br>

			<input type="submit" id="botao" value="Alterar cadastro"><br>
			<br>

		</form>

		<?php include_once("../public/_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
