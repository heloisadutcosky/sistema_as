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

			// Verificar existência do cpf na base ----------------------------------

			$consulta_usuario = "SELECT * FROM usuarios WHERE cpf = " . $cpf;

			$acesso = mysqli_query($conecta, $consulta_usuario);
			$existe_usuario = mysqli_fetch_assoc($acesso);

			if (!empty($existe_usuario)) { ?>
				<p>Já existe um cadastro com esse cpf</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO usuarios (cpf, nome, sexo, nascimento, escolaridade, email, telefone, funcao) VALUES ('$cpf', '$nome', '$sexo', '$nascimento', '$escolaridade', '$email', '$telefone', '$funcao')";

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

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco"><?php 
				if ($acao == "alteracao") echo "ALTERAÇÃO DE ";
				if ($acao == "exclusao") echo "EXCLUSÃO DE "; 
				?>CADASTRO</h2>

			<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $user_id; ?>" method="post">
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
					<input type="date" id="nascimento" name="nascimento" value="<?php echo $dados["nascimento"] ?>">
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
					<label for="funcao">Tipo de avaliador: </label>
					<select id="funcao" name="funcao"><br>
						<option value="Consumidor" <?php if($dados["funcao"] == "Consumidor") { ?> selected <?php } ?>>Consumidor</option>
						<option value="Painelista" <?php if($dados["funcao"] == "Painelista") { ?> selected <?php } ?>>Painelista</option>
						<option value="Candidato" <?php if($dados["funcao"] == "Candidato") { ?> selected <?php } ?>>Candidato</option>
						<option value="Administrador" <?php if($dados["funcao"] == "Administrador") { ?> selected <?php } ?>>Administrador</option>
						<option value="Cliente" <?php if($dados["funcao"] == "Cliente") { ?> selected <?php } ?>>Cliente</option>
					</select><br><br>
				</div>

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
	// Fechar conexão
	mysqli_close($conecta);
?>
