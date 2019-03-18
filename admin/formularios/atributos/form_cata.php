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

	// Informação de número de formulários relacionados ao projeto
	if (isset($_POST["n_mais"])) {
		$n_atributos = $_POST["n_atributos"]+1;
	} else if (isset($_POST["n_menos"])) {
		$n = $_POST["n_menos"];
		if (!empty($_POST["atributo{$n}"])) {
			$excluir = "DELETE FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_completo_port = {$_POST["atributo_completo_port{$n}"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			$n_atributos = $_POST["n_atributos"]-1;
		}
	} else if (isset($_POST["n_atributos"])) { 
		$n_atributos = $_POST["n_atributos"];
	} else {
		$n_atributos = 1;
	}

	// Consultar atributos
	$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
	$acesso = mysqli_query($conecta, $consulta);

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	$conjunto_atributos = isset($_POST["conjunto_atributos"]) ? utf8_decode($_POST["conjunto_atributos"]) : $dados["conjunto_atributos"];
	$descricao_conjunto = isset($_POST["descricao_conjunto"]) ? utf8_decode($_POST["descricao_conjunto"]) : $dados["descricao_conjunto"];

	if (isset($_POST["completo"])) {
		
		$n=1;
		while ($n<=$n_atributos) {
			$atributo = utf8_decode($_POST["atributo{$n}"]);
			$definicao_atributo = "";
			$atributo_completo_port = utf8_decode($_POST["atributo_completo_port{$n}"]);
			$atributo_completo_eng = utf8_decode($_POST["atributo_completo_eng{$n}"]);


			if (!empty($_POST["atributo{$n}"])) {
			// Cadastrar / Alterar -------------------------------------------------------
				if ($acao == "cadastro" || $acao == "alteracao") {

					// Verificar existência do atributo na base ------------------------------

					$consulta_atributo = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_completo_eng = '{$atributo_completo_eng}'";

					$acesso = mysqli_query($conecta, $consulta_atributo);
					$existe_atributo = mysqli_fetch_assoc($acesso);

					if (!empty($existe_atributo)) { 
						$alterar = "UPDATE atributos SET formulario_id = {$_SESSION["formulario_id"]}, conjunto_atributos = '{$conjunto_atributos}', descricao_conjunto = '{$descricao_conjunto}', atributo = '{$atributo}', definicao_atributo = '{$definicao_atributo}', atributo_completo_port = '{$atributo_completo_port}', atributo_completo_eng = '{$atributo_completo_eng}' WHERE atributo_id = {$atributo_id}";
						$operacao_alterar = mysqli_query($conecta, $alterar);
					} 

					// ----------------------------------------------------------------------
						
					else {
						$cadastrar = "INSERT INTO atributos (formulario_id, conjunto_atributos, descricao_conjunto, atributo, definicao_atributo, atributo_completo_eng, atributo_completo_port) VALUES ({$_SESSION["formulario_id"]}, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$definicao_atributo', '$atributo_completo_eng', '$atributo_completo_port')";

						$operacao_cadastrar = mysqli_query($conecta, $cadastrar);
					}
				}
				// --------------------------------------------------------------------------

				// Excluir cadastro ---------------------------------------------------------
				if ($acao == "exclusao") {
						
					$excluir = "DELETE FROM atributos WHERE atributo_id = {$atributo_id}";

					$operacao_excluir = mysqli_query($conecta, $excluir);

					if (!$operacao_excluir) {
						die("Falha na exclusão dos dados.");
					} 
				}
				// --------------------------------------------------------------------------
				$n=$n+1;
			}
		}
		header("location:dados.php");
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

			<div style="background-color: #F8F8F8; padding: 5px 5px 15px 5px; width: 600px">
				<p style="margin-left: 10px; float: left; margin-right: 10px"><b>Conjunto de atributos: </b></p>

				<div style="margin-top: 15px">
					<input type="text" id="conjunto_atributos" name="conjunto_atributos" value="<?php echo utf8_encode($conjunto_atributos); ?>" style= "width: 370px;">
				</div><br>

				<div>
					<label for="descricao_conjunto">Explicação de como avaliá-lo: </label>
					<input type="text" id="descricao_conjunto" name="descricao_conjunto" value="<?php echo utf8_encode($descricao_conjunto); ?>" style= "width: 550px; height: 40px;">
				</div>
			</div><br>

			<?php 
				$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
				$acesso = mysqli_query($conecta, $consulta);

				if (($acao == "alteracao" || $acao == "exclusao") && (!isset($_POST["conjunto_atributos"]))) {

					$n_atributos = mysqli_num_rows($acesso);
					if ($n_atributos==0) {
						$n_atributos=1;
					}
				}

				$n = 1;
				while ($n <= $n_atributos) {

					if (isset($_POST["atributo{$n}"])) {
						$dados = mysqli_fetch_assoc($acesso);
						$atributo = utf8_encode($_POST["atributo{$n}"]);
						//$definicao_atributo = $_POST["definicao_atributo{$n}"];
						$atributo_completo_port = utf8_encode($_POST["atributo_completo_port{$n}"]);
						$atributo_completo_eng = utf8_encode($_POST["atributo_completo_eng{$n}"]);
					} else {
						$dados = mysqli_fetch_assoc($acesso);
						$atributo = utf8_encode($dados["atributo"]);
						$definicao_atributo = utf8_encode($dados["definicao_atributo"]);
						$atributo_completo_port = utf8_encode($dados["atributo_completo_port"]);
						$atributo_completo_eng = utf8_encode($dados["atributo_completo_eng"]);
					}
			?>
			
			<div style="background-color: #F8F8F8; padding: 5px 5px 15px 5px; width: 600px">
				<p style="margin-left: 10px; float: left; margin-right: 10px"><b>Atributo:</b> </p>
				
				<div style="margin-top: 15px">
					<input type="text" id="atributo" name="atributo<?php echo $n; ?>" value="<?php echo utf8_encode($atributo); ?>" style= "width: 470px;">
				</div><br>

				<div style="margin-bottom: 1px;">
					<div style="float: left; margin-right: 30px;">
						<label for="atributo_completo_port">Nome completo em português<small><sup>*</sup></small>:</label>
						<input type="text" id="atributo_completo_port" name="atributo_completo_port<?php echo $n; ?>" value="<?php echo utf8_encode($atributo_completo_port); ?>" style="width: 250px; margin-bottom: 10px;">
					</div>

					<div>
						<label for="atributo_completo_eng">Nome completo em inglês<small><sup>*</sup></small>:</label>
						<input type="text" id="atributo_completo_eng" name="atributo_completo_eng<?php echo $n; ?>" value="<?php echo utf8_encode($atributo_completo_eng); ?>" style="width: 250px; margin-bottom: 10px;">
					</div>
					<small style="font-size: 55%; margin-left: 10px; width: 200px"><sup>*</sup>Nomes que aparecerão na planilha de resultados</small>
				</div>

				<input type="hidden" name="n_atributos" value="<?php echo($n_atributos); ?>">

				<?php if ($n == $n_atributos) { ?>
				<div style="float: right; margin-top: -15px; margin-right: 40px">
					<button name="n_menos" type="submit" value="<?php echo $n; ?>" style="width: 40px; margin-top: 10px; font-size: 120%; background-color: #FFF; color: #778899; text-align: center; padding: 0px; float: left; margin-right: 7px">-</button>
					
						<button name="n_mais" type="submit" value="<?php echo $n; ?>" style="width: 40px; margin-top: 10px; font-size: 120%; background-color: #FFF; color: #778899; text-align: center; padding: 0px">+</button>
				</div><br>
				<?php } ?>

			</div><br>
			<?php $n = $n + 1;
			} ?>
			
			<br>
			<input type="submit" id="botao" name="completo" value="<?php 
				if ($acao == "alteracao") echo "Alterar conjunto";
				elseif ($acao == "exclusao") echo "Excluir conjunto";
				else echo "Cadastrar conjunto";
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