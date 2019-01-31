<?php require_once("conexao/conexao.php"); ?>

<?php  
	// Iniciar sessão
	session_start();
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
		</header>
		<h2 class="espaco">ACESSO AO SISTEMA</h2>
		<br>

		<form action="login.php" method="post">
			<!-- LOGIN -->
			<div class="login">
				<label for="cpf">CPF: </label>
				<input type="text" id="cpf" name="cpf" size="32" placeholder="Insira seu CPF (somente números)" required><br>

				<label for="senha">Senha: </label>
				<input type="password" id="senha" name="senha" size="30" placeholder="Data de nascimento (DDMMAAAA)" required><br>
			</div>

			<input type="submit" id="botao" value="Acessar">
		</form>

		<small><a href="public/cadastro.php" style="color: grey; text-decoration: none; margin-top: -5px">Ainda não estou cadastrado</a></small>

				<?php if (isset($_POST["cpf"])) {
					$cpf = $_POST["cpf"];
					$senha = $_POST["senha"];

					$consulta = "SELECT * FROM usuarios WHERE cpf = '{$cpf}'";
					$acesso = mysqli_query($conecta, $consulta);
					$dados = mysqli_fetch_assoc($acesso);

					// CPF não encontrado na base
					if (!empty($dados)) { 
						$_SESSION["user_id"] = $dados["user_id"];
						$_SESSION["usuario"] = utf8_encode($dados["nome"]);
						$_SESSION["funcao"] = $dados["funcao"];
						$nascimento = date("dmY", strtotime($dados["nascimento"]));
							
						if ($senha == $nascimento) {
							if($_SESSION["funcao"]=="Administrador") { 
								header("location:admin/principal.php"); // Redireciona
							} else {
								header("location:public/principal.php");
							}
						} else { ?>
							<p><b style="color: #8B0000">Senha incorreta.</b></p>
						<?php }
					} else { ?>
						<p>Usuário não cadastrado.</p>
						<div class="botao">
							<a href="public/cadastro.php">Realizar cadastro</a>
						</div>
					<?php } 
				} ?>

		<br>
		<br>
		<br>
		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
