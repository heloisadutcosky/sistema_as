<?php require_once("../conexao/conexao.php"); ?>

<?php  
	// Iniciar sessão
	session_start();

	// Abrir consulta ao banco de dados
	if (isset($_GET["projeto_id"])) {
		$projetoID = $_GET["projeto_id"];

		$consulta = "SELECT * FROM formularios WHERE projeto_id = {$projetoID}";
		$acesso = mysqli_query($conecta, $consulta);

		if (!$acesso) {
			die("Falha na consulta ao banco.");
		}

		$dados = mysqli_fetch_assoc($acesso);

		if (isset($_POST["conjunto_atributos"])) {

			// Inserir nova linha de formulário no sistema

			$conjunto_atributos = utf8_decode($_POST["conjunto_atributos"]);
			$descricao_conjunto = utf8_decode($_POST["descricao_conjunto"]);
			$atributo = utf8_decode($_POST["atributo"]);
			$atributo_short = $_POST["atributo"];
			$escala_baixo = utf8_decode($_POST["escala_baixo"]);
			$escala_alto = utf8_decode($_POST["escala_baixo"]);

			$inserir = "INSERT INTO formularios (projeto_id, conjunto_atributos, descricao_conjunto, atributo, atributo_short, escala_baixo, escala_alto, escala_min, escala_max) VALUES ('$projetoID', '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$atributo_short', '$escala_baixo', '$escala_alto', '$escala_min', '$escala_max')";

			$operacao_inserir = mysqli_query($conecta, $inserir);

			if (!$operacao_inserir) {
				die("Falha na insercao ao banco.");
			}

			// Passar para próxima página
			header("location:amostras.php");
		} 

	// Liberar dados da memória
	mysqli_free_result($acesso);
?>	


<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Cadastro About Solution</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="_css/estilo.css">

</head>
<body>
	<main>
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
				<img src="imagens/logo.jpg" width="210" height="70"
				title="logo About Solution">
			</a>
			<h2 class="espaco">CADASTRO</h2>
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
		<form action="cadastro.php" method="post">

			<label for="conjunto atributos">Conjunto de atributos</label>
			<input type="text" id="cpf" name="cpf" placeholder="Insira seu CPF (somente números)" required>

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

			<label for="aceito">Aceito blablabla</label>
			<input type="checkbox" id="aceito" name="aceito"><br>
			<br>

			<input type="submit" id="botao" value="Realizar cadastro"><br>
			<br>

		</form>

		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
