<?php 

	$caminho =  "../../../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	

	if (isset($_GET["first"])) {
		$_SESSION["first"] = $_GET["first"];
	}

	$projeto_id = $_SESSION["projeto_id"];
	$sessao = $_SESSION["sessao"];

	$_SESSION["atributo_id"] = !empty($_SESSION["atributo_id"]) ? $_SESSION["atributo_id"] : 0;

			
	if (isset($_POST["atributo" . $_SESSION["atributo_id"][0]])) {

		foreach ($_SESSION["atributo_id"] as $atributo_id) {

			$nota = $_POST["atributo" . $atributo_id]*10;

			$consulta_resultados = "SELECT * FROM resultados WHERE projeto_id = {$projeto_id} AND sessao = {$sessao} AND user_id = {$user_id} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id}";
			$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
			$resultados = mysqli_fetch_assoc($acesso_resultados);


			if (empty($resultados)) {
				$inserir = "INSERT INTO resultados (projeto_id, sessao, user_id, amostra_codigo, atributo_id, nota, teste) VALUES ($projeto_id, $sessao, $user_id, '{$_SESSION["amostra"]}', $atributo_id, $nota, {$_SESSION["teste"]})";

				$operacao_inserir = mysqli_query($conecta, $inserir);
			} else {

				$alterar = "UPDATE resultados SET nota = {$nota} WHERE projeto_id = {$projeto_id} AND sessao = {$sessao} AND user_id = {$user_id} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id}";

				$operacao_alterar = mysqli_query($conecta, $alterar);
			}
			
		}
	}
		

	// ##########################################################################################################################


	// Verificar dados já preenchidos ###########################################################################################

	if (isset($_POST["amostra"])) {
		$_SESSION["amostra"] = $_POST["amostra"];

		$atributos_id = array_keys(array_diff($_SESSION["atributos_id"], array("Aparência")));
		$atributo_id = $atributos_id[0];

		$preenchido=1;
		while ($preenchido==1) {
			$consulta = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND atributo_id = '{$atributo_id}' AND amostra_codigo = '{$_SESSION["amostra"]}'";
			
			$acesso = mysqli_query($conecta, $consulta);
			$n_resultados = mysqli_num_rows($acesso);

			if ($n_resultados == 0) {
				$preenchido=0;

				$atributo_id = !empty($atributo_id) ? $atributo_id : 0;

				$consulta = "SELECT * FROM formularios WHERE atributo_id = {$atributo_id}";
				$acesso = mysqli_query($conecta, $consulta);
				$dados = mysqli_fetch_assoc($acesso);

				$_SESSION["conjunto_atributos"] = $dados["conjunto_atributos"];
				$_SESSION["atributo_id"] = array_keys($_SESSION["atributos_id"], $_SESSION["conjunto_atributos"]);
				$_SESSION["n_atributos"] = count($_SESSION["atributo_id"]);
				$descricao_conjunto = utf8_encode($dados["descricao_conjunto"]);
			}

			$atributo_id = next($atributos_id);
		}
	} else {
		$_SESSION["conjunto_atributos"] = "";
	}

	if (empty($_SESSION["conjunto_atributos"])) {
		
		if (!isset($_POST["amostra"])) {
			$_SESSION["amostra"] = $_SESSION["amostras"][0];
		} else {
			$_SESSION["amostra"] = $_SESSION["amostras"][array_search($_SESSION["amostra"], $_SESSION["amostras"])+1];
			if (!$_SESSION["amostra"]) {
			 	if ($_SESSION["first"] == 1) {
					header("location:aparencia.php?first=0");
				} else {
					header("location:principal.php");
				}
			 } 
		} ?>

		<!DOCTYPE html>
		<html lang="pt-BR">
		<head>
			<title>PDQ - Cabines</title>
			<meta charset="utf-8">
			
			<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
			<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">
			
			<style>
				.amostra {
				  font-size: 120%;
				  font-weight: bold;
				  color: #C2534B;
				}
			</style>

		</head>
		<body>
			<main>
				<?php include_once($caminho . "_incluir/topo.php"); ?>

				<article>
					<h2><?php echo $_SESSION["produto"]; ?></h2>
					<br>
					<h3 style="color: #8B0000">CABINES</h3>

					<p>Favor solicitar à atendente a amostra <b class="amostra"><?php echo $_SESSION["amostra"]; ?></b></p>
					<br>

					
					<form action="cabines.php" method="post">
					<input type="hidden" name="amostra" value="<?php echo $_SESSION["amostra"];?>">

					<input type="submit" id="botao" value="Continuar">
					
					</form>
				</article>
				<br>
				<br>

				<?php include_once($caminho . "_incluir/rodape.php"); ?>
				<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>

			</main>
		</body>
		</html>
		
	<?php } else {
	
	// Reabrir consulta ao banco de dados - agora por conjunto
	mysqli_free_result($acesso);

	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND conjunto_atributos = '{$_SESSION["conjunto_atributos"]}'";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>PDQ - Cabines</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo2.css">
	
	<style>
		.amostra {
		  font-size: 120%;
		  font-weight: bold;
		  color: #C2534B;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<h2 style="margin-bottom: 5px;"><?php echo $_SESSION["produto"]; ?></h2>
			
			<div style="margin-top: 40px;">
				<h3 style="font-size: 120%; color: #8B0000;"><?php echo utf8_encode($_SESSION["conjunto_atributos"]); ?></h3>
				
				<p><?php echo $descricao_conjunto; ?></p><br>

				<p class="amostra"><?php echo "Amostra " . $_SESSION["amostra"]; ?></p>

				<ul type="circle">

					<?php 
					
					while($linhas=mysqli_fetch_assoc($acesso)) { ?>					

						<br><br>
						<li><b><?php echo utf8_encode($linhas["atributo"]); ?></b></li>
						<p style="font-size: 95%; font-family: serif;"><?php echo utf8_encode($linhas["definicao_atributo"]); ?></p>

						<div style="position: relative; left: <?php echo($linhas["escala_min"]*80-45); ?>px; width: <?php echo(($linhas["escala_max"]-$linhas["escala_min"])*80+90); ?>px; margin-bottom: 90px; margin-top: 20px">
							<div style="position: absolute; left: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($linhas["referencia_min"]); ?></p>
							</div>
							<div style="position: absolute; right: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($linhas["referencia_max"]); ?></p>
							</div>
						</div>

						<form action="cabines.php" method="post" align="">
							<input type="range" id="nota" name="atributo<?php echo $linhas["atributo_id"]; ?>" min="0" max="10" value="0" step="0.01" style="margin-bottom: 20px; margin-left: 20px" required>
							<input type="checkbox" name="teste" required style="width: 20px; float: right; margin-right: 50px; margin-top: 22px">
							<div class="ticks" style="padding-left: <?php echo($linhas["escala_min"]*80+20); ?>px; width: <?php echo(($linhas["escala_max"]-$linhas["escala_min"])*80-50); ?>px">
								<span class="tick"></span>
								<span class="tick"></span>
							</div>
							<div class="afterticks" style="padding-left: <?php echo($linhas["escala_min"]*80+45); ?>px; width: <?php echo(($linhas["escala_max"]-$linhas["escala_min"])*80-20); ?>px">
								<span class="aftertick"><?php echo utf8_encode($linhas["escala_baixo"]); ?></span>
								<span class="aftertick"><?php echo utf8_encode($linhas["escala_alto"]); ?></span>
							</div>
							<span id="resultado"></span>
							<br><br>
					<?php } ?>
							<input type="hidden" name="amostra" value="<?php echo $_SESSION["amostra"];?>">
							<input type="submit" id="botao" value="Confirmar" style="margin-left: -10px">
						</form>
				</ul><br>
			</div>
		</article>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>
		
	</main>
</body>
</html>

<?php  } ?>

<?php 
	// Fechar conexão
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
mysqli_close($conecta);
?>