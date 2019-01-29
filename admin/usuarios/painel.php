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
		$funcao = "Painelista";

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE usuarios SET cpf = '{$cpf}', nome = '{$nome}', sexo = '{$sexo}', nascimento = '{$nascimento}', escolaridade = '{$escolaridade}', email = '{$email}', telefone = '{$telefone}', funcao = '{$funcao}' WHERE user_id = {$_SESSION["user_id"]}";

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
				
			$cadastrar = "INSERT INTO usuarios (cpf, nome, sexo, nascimento, escolaridade, email, telefone, funcao) VALUES ('$cpf', '$nome', '$sexo', '$nascimento', '$escolaridade', '$email', '$telefone', '$funcao')";

			$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

			if (!$operacao_cadastrar) {
				die("Falha no cadastro dos dados.");
			} else {
				header("location:dados.php");
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

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE ";
			if ($acao == "exclusao") echo "EXCLUSÃO DE "; 
			?>CADASTRO</h2>

		<br>
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $user_id; ?>" method="post">

			<label for="cpf">CPF</label>
			<input type="text" id="cpf" name="cpf" value="<?php echo $dados["cpf"] ?>"><br>

			<label for="nome">Nome</label>
			<input type="text" id="nome" name="nome" value="<?php echo utf8_encode($dados["nome"]) ?>"><br>

			<label for="nascimento">Data de nascimento</label>
			<input type="date" id="nascimento" name="nascimento" value="<?php echo $dados["nascimento"] ?>"><br>

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

			<input type="submit" id="botao" value="<?php 
				if ($acao == "alteracao") echo "Alterar cadastro";
				if ($acao == "exclusao") echo "Excluir cadastro";
				if ($acao == "cadastro") echo "Cadastrar";
			?>"><br>
			<br>

		</form>

		<div class="direita">
			<a href="dados.php">Voltar</a><br><br>
		</div>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
