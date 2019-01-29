<?php 
	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	// Projeto selecionado
	$projeto_id = isset($_GET["codigo"]) ? $_GET["codigo"] : 0; 
	$produto = isset($_GET["produto"]) ? $_GET["produto"] : ""; 

	// Inputs
	if (isset($_POST["atributo"])) {
		$conjunto_atributos = $_POST["conjunto_atributos"];
		$descricao_conjunto = $_POST["descricao_conjunto"];
		$atributo = $_POST["atributo"];
		$atributo_short = $_POST["atributo_short"];
		$atributo_completo = strtolower($_POST["conjunto_atributos"]) . "_" . strtolower($_POST["atributo_short"]);
		$atributo = $_POST["atributo"];
		$escala_baixo = $_POST["escala_baixo"];
		$escala_alto = $_POST["escala_alto"];
		$escala_min = $_POST["escala_min"];
		$escala_max = $_POST["escala_max"];

		$consulta = "SELECT * FROM formularios WHERE projeto_id = " . $projeto_id . " AND atributo_completo = " . $atributo_completo;;

		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);
	} else {
		$conjunto_atributos = "";
		$descricao_conjunto = "";
	}

	// Ver se atributo já foi cadastrado
	if (empty($dados)) {  
		if (isset($_POST["atributo"])) {
			$inserir = "INSERT INTO formularios (projeto_id, conjunto_atributos, descricao_conjunto, atributo, atributo_short, atributo_completo, escala_baixo, escala_alto, escala_min, escala_max) VALUES ($projeto_id, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$atributo_short', '$atributo_completo', '$escala_baixo', '$escala_alto', $escala_min, $escala_max)";
			$operacao_inserir = mysqli_query($conecta, $inserir); 
		}
?>

		<!DOCTYPE html>
		<html lang="pt-BR">
		<head>
		<title>Cadastro Sessão</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

		</head>
		<body>
		<main>
			<?php include_once($caminho . "_incluir/topo.php"); ?>
			<h2 class="espaco">Formulário - <?php echo $produto; ?></h2>
			
				<h4>Cadastro dos atributos pesquisados</h4><br>
				
				<form action="cadastro.php?codigo=<?php echo($projeto_id); ?>" method="post">

						<p>Favor preencher o conjunto de atributos e uma explicação de como avaliá-lo</p>

						<label for="conjunto_atributos">Conjunto de atributos: </label>
						<input type="text" id="conjunto_atributos" name="conjunto_atributos" value="<?php echo $conjunto_atributos; ?>" required><br>

						<label for="descricao_conjunto">Explicação: </label>
						<input type="text" id="descricao_conjunto" name="descricao_conjunto" value="<?php echo $descricao_conjunto; ?>"><br>
						

						<p><b>Favor preencher o atributo avaliado: </b></p>
						
						<label for="atributo">Atributo: </label>
						<input type="text" id="atributo" name="atributo" value="" required><br>
						<small><sup>*</sup>Um por página</small>

						<label for="atributo_short">Nome curto para o atributo:</label>
						<input type="text" id="atributo_short" name="atributo_short" value="" required>
						<small><sup>*</sup>Sem espaços (os nomes das colunas serão formados pelo nome do conjunto de atributos mais o nome curto do atributo</small><br><br>

						<p><b>Favor preencher os textos que devem aparecer nos extremos da régua e os valores correspondentes: </b></p>
						<label for="escala_baixo">Escala baixa: </label>
						<input type="text" id="escala_baixo" name="escala_baixo" value="" required>
						<label for="escala_alto">Escala alta: </label>
						<input type="text" id="escala_alto" name="escala_alto" value="" required><br>

						<label for="escala_min">Valor escala baixa: </label>
						<input type="number" id="escala_min" name="escala_min" value="" required>
						<label for="escala_baixo">Valor escala alta: </label>
						<input type="text" id="escala_baixo" name="escala_baixo" value="" required><br><br>


						<input type="submit" id="botao" value="Cadastrar atributo">
				</form>
				<br>
				<br>

				<div class="botao">
					<a href="cadastro.php?codigo=<?php echo $projeto_id ?>&produto=<?php echo $produto; ?>">Adicionar atributo</a>
				</div>
				<div class="direita">
					<a href="../dados.php">Voltar</a><br><br>
				</div>

				<br>
				<br>
				<?php include_once($caminho . "_incluir/rodape.php"); ?>

			</main>
		</body>
		</html>

<?php }
	else { ?>
		<p>Esse atributo já foi cadastrado no formulário desse projeto</p>
	<?php } ?>
