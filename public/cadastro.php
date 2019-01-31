<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

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
				$nascimento = $_POST["nascimento"];
				$escolaridade = utf8_decode($_POST["escolaridade"]);
				$email = $_POST["email"];
				$telefone = $_POST["telefone"];
				$funcao = $_POST["funcao"];

				$inserir = "INSERT INTO usuarios (cpf, nome, sexo, nascimento, escolaridade, email, telefone, funcao) VALUES ('$cpf', '$nome', '$sexo', '$nascimento', '$escolaridade', '$email', '$telefone', '$funcao')";

				$operacao_inserir = mysqli_query($conecta, $inserir);

				if (!$operacao_inserir) {
					die("Falha na insercao ao banco.");
				}

				// Recuperar user_id criado
				$acesso = mysqli_query($conecta, $consulta);
				$dados = mysqli_fetch_assoc($acesso);

				$_SESSION["user_id"] = $dados["user_id"];
				$_SESSION["usuario"] = utf8_encode($dados["nome"]);
				$_SESSION["funcao"] = $dados["funcao"];

				if($_SESSION["funcao"]=="Consumidor") { 
					header("location:../principal.php"); // Redireciona
				} else {
					header("location:painelista/cadastro_painel.php");
				} // Redireciona
			}


		} else { ?>
			<p>Esse cpf já foi cadastrado.</p>
			<p>Favor realizar o login.</p>
			<a href="<?php echo $caminho; ?>login.php">Login</a>
		<?php } 
	} 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Cadastro About Solution</title>
	
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
		<h2 class="espaco">CADASTRO USUÁRIO</h2>
		<br>

		
		<form action="cadastro.php" method="post">

			<!-- CADASTRO -->
			<div class="folha_cadastro">
				<label for="nome">Nome: </label>
				<input type="text" id="nome" name="nome" placeholder="Insira seu nome" required><br>

				<label for="cpf">CPF: </label>
				<input type="text" id="cpf" name="cpf" size="30" placeholder="Insira seu CPF (somente números)" required><br>

				<label for="nascimento">Data de nascimento: </label>
				<input type="date" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa" required><br>

				<label for="sexo">Sexo: </label>			
				<select id="sexo" name="sexo">
					<option value="Feminino">Feminino</option>
					<option value="Masculino">Masculino</option>
				</select><br>

				<label for="escolaridade">Escolaridade</label>
				<select id="escolaridade" name="escolaridade"><br>
					<option value="Ensino Fundamental">Ensino Fundamental</option>
					<option value="Ensino Médio">Ensino Médio</option>
					<option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
					<option value="Ensino Superior Completo">Ensino Superior Completo</option>
				</select><br>

				<label for="email">E-mail</label>
				<input type="email" id="email" name="email"><br>

				<label for="telefone">Telefone</label>
				<input type="tel" id="telefone" name="telefone"><br>

				<label for="funcao">Tipo de avaliador: </label>
				<select id="funcao" name="funcao">
					<option value="Consumidor">Consumidor</option>
					<option value="Painelista" selected>Painelista</option>
					<option value="Candidato">Candidato</option>
				</select>
			</div>
			<br>

			<input type="submit" id="botao" value="Realizar cadastro"><br>
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
