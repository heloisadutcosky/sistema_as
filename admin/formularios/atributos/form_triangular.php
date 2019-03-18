<?php

	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "cadastro";
	$atributo_id = isset($_GET["atributo"]) ? $_GET["atributo"] : 0;

	// Consultar atributos
	$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
	$acesso = mysqli_query($conecta, $consulta);

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	$conjunto_atributos = "";
	$descricao_conjunto = "";

	if (isset($_POST["definicao_atributo"])) {
		$atributo = "Triangular";
		$definicao_atributo = utf8_decode($_POST["definicao_atributo"]);
		$atributo_completo_port = "triangular";
		$atributo_completo_eng = "triangular";


		if (isset($_POST["completo"])) {
		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE atributos SET formulario_id = {$_SESSION["formulario_id"]}, conjunto_atributos = '{$conjunto_atributos}', descricao_conjunto = '{$descricao_conjunto}', atributo = '{$atributo}', definicao_atributo = '{$definicao_atributo}', atributo_completo_port = '{$atributo_completo_port}', atributo_completo_eng = '{$atributo_completo_eng}' WHERE atributo_id = {$atributo_id}";

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

			// Verificar existência do atributo na base ------------------------------

			$consulta_atributo = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_completo_eng = '{$atributo_completo_eng}'";

			$acesso = mysqli_query($conecta, $consulta_atributo);
			$existe_atributo = mysqli_fetch_assoc($acesso);

			if (!empty($existe_atributo)) { 
				$atributo_id_temp = 0; ?>
				<p>Esse atributo já foi cadastrado nesse projeto</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO atributos (formulario_id, conjunto_atributos, descricao_conjunto, atributo, definicao_atributo, atributo_completo_eng, atributo_completo_port) VALUES ({$_SESSION["formulario_id"]}, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$definicao_atributo', '$atributo_completo_eng', '$atributo_completo_port')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
				} else {
					$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_completo_eng = '{$atributo_completo_eng}'";
					$acesso = mysqli_query($conecta, $consulta);
					$dados_opcoes = mysqli_fetch_assoc($acesso);
					$atributo_id = $dados_opcoes["atributo_id"];

					header("location:dados.php");
				}
			}
		}
		// --------------------------------------------------------------------------

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM atributos WHERE atributo_id = {$atributo_id}";

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
} else {
	$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
	$acesso = mysqli_query($conecta, $consulta);

	$definicao_atributo = $dados["definicao_atributo"];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Atributos</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2><?php 
			if ($acao == "alteracao") echo "Alteração de ";
			elseif ($acao == "exclusao") echo "Exclusão de ";
			else echo "Cadastro de Atributo - " . $_SESSION["nome_formulario"]; 
			?></h2>
		
		<form action="?acao=<?php echo $acao; ?>&atributo=<?php echo $atributo_id; ?>" method="post">
			
			<div style="background-color: #F8F8F8; padding: 5px 5px 5px 5px; width: 600px">
				<p style="margin-left: 10px;"><b>Teste triangular:</b></p>

				<div>
					<label for="descricao_conjunto">Pergunta ao consumidor: </label>
					<input type="text" id="definicao_atributo" name="definicao_atributo" value="<?php echo utf8_encode($definicao_atributo); ?>" style= "width: 550px; height: 40px;">
				</div><br>
			</div><br><br>
			
			<input type="submit" id="botao" name="completo" value="<?php 
				if ($acao == "alteracao") echo "Alterar atributo";
				elseif ($acao == "exclusao") echo "Excluir atributo";
				else echo "Cadastrar atributo";
			?>" style="margin-left: 5px; float: left; margin-right: 200px">

		</form>
		<br><br>
		</article><br><br>

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
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
if (isset($acesso2)) {
	mysqli_free_result($acesso2);
}
	mysqli_close($conecta);
?>