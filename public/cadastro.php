<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start(); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Cadastro About Solution</title>
	
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

		<article style="margin-left: 10px">
		<h2 style="margin-left: 20px">CADASTRO USUÁRIO</h2>
		<br>

		<?php

		if (isset($_POST["cpf"])) {

		$cpf = $_POST["cpf"];

		$consulta = "SELECT * FROM usuarios WHERE cpf = '{$cpf}'";
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);

		// CPF não encontrado na base
		if (empty($dados)) { 
			// Armazenar informações
			
			if (isset($_POST["nome"])) { 
				$nome = utf8_decode($_POST["nome"]);
				$sexo = $_POST["sexo"];
				$nascimento = date("Y-m-d", strtotime($_POST["nascimento"]));
				$escolaridade = utf8_decode($_POST["escolaridade"]);
				$email = $_POST["email"];
				$telefone = $_POST["telefone"];
				$funcao = $_POST["funcao"];

				$words = explode(" ", $nome);
				$iniciais = "";

				foreach ($words as $w) {
		  			$iniciais .= $w[0];
				}

				$inserir = "INSERT INTO usuarios (cpf, nome, sexo, nascimento, escolaridade, email, telefone, funcao, iniciais) VALUES ('$cpf', '$nome', '$sexo', '$nascimento', '$escolaridade', '$email', '$telefone', '$funcao', '$iniciais')";

				$operacao_inserir = mysqli_query($conecta, $inserir);

				if (!$operacao_inserir) {
					echo $inserir;
					die("Falha na insercao ao banco.");
				}

				// Recuperar user_id criado
				$acesso = mysqli_query($conecta, $consulta);
				$dados = mysqli_fetch_assoc($acesso);

				$_SESSION["user_id"] = $dados["user_id"];
				$_SESSION["usuario"] = utf8_encode($dados["nome"]);
				$_SESSION["funcao"] = $dados["funcao"];

				if($_SESSION["funcao"]=="Consumidor") { 
					header("location:principal.php"); // Redireciona
				} else {
					header("location:cadastro_painel.php");
				} // Redireciona
			}


		} else { ?>
			<div style="width: 600px; background-color: #F8E0E0; padding: 2px 5px; margin-left: 20px">
				<p style="margin-left: 10px">Esse cpf já está cadastrado no sistema.</p>
				<p style="margin-left: 10px">Favor realizar o <a href="<?php echo $caminho; ?>login.php">login</a>.</p>
			</div>
			<br><br>
		<?php } 
	} ?>

		
		<form action="cadastro.php" method="post">

			<!-- CADASTRO -->

			<div style="background-color: #F8F8F8; padding: 15px 5px 15px 5px; width: 600px; margin-left: 20px">
			<div style="float: left; margin-right: 30px;">
				<label for="nome">Nome: </label>
				<input type="text" id="nome" name="nome" placeholder="Insira seu nome" required style="width: 250px">
			</div>

			<div>
				<label for="cpf">CPF: </label>
				<input type="text" id="cpf" name="cpf" placeholder="Insira seu CPF (somente números)" required style="width: 250px"><br>
			</div>

			<div style="float: left; margin-right: 30px;">
					<label for="nascimento">Data de nascimento: </label>
					<input type="datetime" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa" required style="width: 250px">
				</div>

				<div>
					<label for="sexo">Sexo: </label>			
					<select list="sexos" id="sexo" name="sexo" selected="<?php echo $dados["sexo"] ?>" style="width: 250px">
							<option value="Feminino" selected>Feminino</option>
							<option value="Masculino">Masculino</option>
					</select>
				</div><br>

				<div>
					<label for="escolaridade">Escolaridade: </label>
					<select id="escolaridade" name="escolaridade" style="width: 250px"><br>
						<option value="Ensino Fundamental" selected>Ensino Fundamental</option>
						<option value="Ensino Médio">Ensino Médio</option>
						<option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
						<option value="Ensino Superior Completo">Ensino Superior Completo</option>
					</select>
				</div><br>

				<div style="float: left; margin-right: 30px;">
					<label for="email">E-mail: </label>
					<input type="email" id="email" name="email" style="width: 250px">
				</div>

				<div>
					<label for="telefone">Telefone: </label>
					<input type="tel" id="telefone" name="telefone" style="width: 250px">
				</div><br><br>

				<div>
					<label for="funcao">Tipo de avaliador: </label>
					<select id="funcao" name="funcao" style="width: 250px"><br>
						<option value="Consumidor" selected>Consumidor</option>
						<option value="Painelista">Painelista</option>
						<option value="Candidato">Candidato</option>
					</select>
				</div>
				</div><br><br>

				<div style="margin-left: 15px">
					<input type="submit" id="botao" value="Realizar cadastro">
				</div>

			</form>
			</article>

		<br><br>
		<?php include_once($caminho . "_incluir/rodape.php"); ?>

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