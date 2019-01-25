<?php require_once("../conexao/conexao.php"); ?>

<?php  
	// Iniciar sessão
	session_start();

	if (isset($_POST["cpf"])) {

		$cpf = $_POST["cpf"];

		$consulta = "SELECT * FROM usuarios WHERE cpf = '{$cpf}'";

		$acesso = mysqli_query($conecta, $consulta);

		if (!$acesso) {
			die("Falha na consulta ao banco.");
		}

		$dados = mysqli_fetch_assoc($acesso);

		// CPF não encontrado na base
		if (empty($dados)) { 

			$cadastro = TRUE;
			
			// Armazenar informações
			
			if (isset($_POST["nome"])) { 

				$nome = utf8_decode($_POST["nome"]);
				$sexo = $_POST["sexo"];
				$nascimento = $_POST["nascimento"];
				$escolaridade = utf8_decode($_POST["escolaridade"]);
				$email = $_POST["email"];
				$telefone = $_POST["telefone"];
				$funcao = "Painelista";

				$inserir = "INSERT INTO usuarios (cpf, nome, sexo, nascimento, escolaridade, email, telefone, funcao) VALUES ('$cpf', '$nome', '$sexo', '$nascimento', '$escolaridade', '$email', '$telefone', '$funcao')";

				$operacao_inserir = mysqli_query($conecta, $inserir);

				if (!$operacao_inserir) {
					die("Falha na insercao ao banco.");
				}

				$acesso = mysqli_query($conecta, $consulta);
				$dados = mysqli_fetch_assoc($acesso);

				$_SESSION["user_id"] = $dados["user_id"];
				$_SESSION["usuario"] = utf8_encode($dados["nome"]);
				header("location:sessoes.php"); // Redireciona

			}
				
			

		} else {
			$_SESSION["user_id"] = $dados["user_id"];
			$_SESSION["usuario"] = $dados["nome"];
			$_SESSION["funcao"] = $dados["funcao"];
			header("location:sessoes.php"); // Redireciona
		}

	} 
	
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Login About Solution</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="_css/estilo.css">

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
			<h2 class="espaco">ACESSO AO SISTEMA</h2>
		</header>
		<br>

		
		<form action="login.php" method="post">

			<!-- LOGIN -->
			<div class="login">
				<label for="cpf">CPF</label>
				<?php if(isset($cpf)) { ?>
				<input type="text" id="cpf" name="cpf" size="30" placeholder="Insira seu CPF (somente números)" value="<?php echo $cpf; ?>" required><br>
				<?php } else { ?>
				<input type="text" id="cpf" name="cpf" size="30" placeholder="Insira seu CPF (somente números)" required><br>
				<?php } ?>
			</div>

			<!-- CADASTRO -->

		
			<?php if (isset($cadastro)) { ?>

				<div class="folha_cadastro">

					<p>Você ainda não está cadastrado no sistema da About Solution!</p>
					<p>Por favor, complete as informações abaixo para realizar seu cadastro.</p>
					<br>

					<label for="nome">Nome</label>
					<input type="text" id="nome" name="nome" placeholder="Insira seu nome" required><br>

					<label for="nascimento">Data de nascimento</label>
					<input type="date" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa" required><br>

					<label for="sexo">Sexo</label>			
					<select id="sexo" name="sexo">
						<option value="Feminino">Feminino</option>
						<option value="Masculino">Masculino</option>
					</select><br><br>

					<label for="escolaridade">Escolaridade</label>
					<select id="escolaridade" name="escolaridade"><br>
						<option value="Ensino Fundamental">Ensino Fundamental</option>
						<option value="Ensino Médio">Ensino Médio</option>
						<option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
						<option value="Ensino Superior Completo">Ensino Superior Completo</option>
					</select><br><br>

					<label for="email">E-mail</label>
					<input type="email" id="email" name="email"><br>

					<label for="telefone">Telefone</label>
					<input type="tel" id="telefone" name="telefone"><br>
					<br>

				</div>
			<?php } ?>

			<input type="submit" id="botao" value="Acessar"><br>
		</form>

		<br>
		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
