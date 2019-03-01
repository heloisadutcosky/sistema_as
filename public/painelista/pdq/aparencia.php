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
	
	$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;

	// Armazenar respostas anteriores
	if (isset($_POST["amostra{$_SESSION["amostras"][0]}"])) {
	
		foreach ($_SESSION["amostras"] as $amostra) {

			$nota = $_POST["amostra{$amostra}"];

			$consulta_resultados = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$amostra}' AND atributo_id = {$_SESSION["atributo_id"]}";
			$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
			$resultados = mysqli_fetch_assoc($acesso_resultados);


			if (empty($resultados)) {
				$inserir = "INSERT INTO resultados (projeto_id, sessao, user_id, amostra_codigo, atributo_id, nota, teste) VALUES ({$_SESSION["projeto_id"]}, {$_SESSION["sessao"]}, {$_SESSION["user_id"]}, '$amostra', {$_SESSION["atributo_id"]}, $nota, {$_SESSION["teste"]})";

				echo $inserir;

				$operacao_inserir = mysqli_query($conecta, $inserir);
			} else {

				$alterar = "UPDATE resultados SET nota = {$nota} WHERE projeto_id = {$_SESSION["projeto_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$amostra}' AND atributo_id = {$_SESSION["atributo_id"]}";

				$operacao_alterar = mysqli_query($conecta, $alterar);
			}
		}
	}

	$atributos_id = array_keys($_SESSION["atributos_id"], "Aparência");
	$atributo_id = $atributos_id[0];
	$preenchido=1;
	while ($preenchido==1) {
		$consulta_resultados = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND atributo_id = $atributo_id";
	
		$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
		$n_resultados = mysqli_num_rows($acesso_resultados);
		

		if ($n_resultados!=count($_SESSION["amostras"])) {
			$preenchido=0;
			$_SESSION["atributo_id"] = $atributo_id;
		}

		$atributo_id = next($atributos_id);
	}

	if (empty($_SESSION["atributo_id"])) {
		if ($_SESSION["first"] == 1) {
			header("location:cabines.php?first=0");
		} else {
			header("location:principal.php");
		}
	} 

	// Próximas variáveis
	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$_SESSION["projeto_id"]} AND atributo_id={$_SESSION["atributo_id"]}";
	$acesso = mysqli_query($conecta, $consulta);
	$dados = mysqli_fetch_assoc($acesso);

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>PDQ - Aparência</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo2.css">

	<style type="text/css">
		.amostra {
			float: left;
			width: 60px;
			margin: 18px 0 0 5px;
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
			
		<h2 style="margin-left: 10px"><?php echo $_SESSION["produto"]; ?></h2>
			<!-- Título do atributo avaliado -->
			<div style="margin-top: 30px; margin-left: 10px">
				<h3 style="font-size: 120%; color: #8B0000;"><?php echo utf8_encode($dados["conjunto_atributos"]); ?></h3>
				<!-- Explicação do teste -->
				<p><?php echo utf8_encode($dados["descricao_conjunto"]); ?></p><br>
			</div>


				<div style="margin-left: -5px">
				<ul type="circle">

						<li style="font-size: 120%; float: left; margin-right: 10px"><b><?php echo utf8_encode($dados["atributo"]); ?></b></li>
						<p style="font-size: 100%; font-family: serif; padding-top: 2px; margin-bottom: -5px">(<?php echo utf8_encode($dados["definicao_atributo"]); ?>)</p><br>

						<div style="position: relative; width: <?php echo(($dados["escala_max"]-$dados["escala_min"])*80+90); ?>px; margin-bottom: 70px; margin-left: <?php echo($dados["escala_min"]*80-15); ?>px; float: center;">
							<div style="position: absolute; left: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($dados["referencia_min"]); ?></p>
							</div>
							<div style="position: absolute; right: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($dados["referencia_max"]); ?></p>
							</div>
						</div>
						
						<div class="reguas" style="margin-left: -10px">

							<form action="aparencia.php" method="post" align="">
							<?php foreach ($_SESSION["amostras"] as $amostra) { ?>

								<p class="amostra"><?php echo $amostra; ?></p>
								
									<input type="range" id="nota<?php echo $amostra; ?>" name="nota<?php echo $amostra; ?>" min="0" max="10" value="0" step="0.01" style="margin-bottom: 20px;" required>
									<input type="text" id="amostra<?php echo $amostra; ?>" name="amostra<?php echo $amostra; ?>" style="width: 30px; margin-left: 5px; border: none; text-align: center;">
									<input id="<?php echo $amostra; ?>" type="checkbox" name="teste" style="width: 20px; float: right; margin-right: 20px; margin-top: 22px" onclick="ShowHideDiv(this.id)">

									<script type="text/javascript">
										function ShowHideDiv(clickedId) {
								        	var travar = document.getElementById(clickedId).checked ? 1 : 0;
								        	if (travar == 1) {
								        		var nota = document.getElementById("nota".concat(clickedId)).value*10;
								        		document.getElementById("nota".concat(clickedId)).disabled = true;		
								        		document.getElementById("amostra".concat(clickedId)).value = nota.toFixed(1);
								        	} 
								        	else {
								        		document.getElementById("nota".concat(clickedId)).disabled = false;
								        		document.getElementById("amostra".concat(clickedId)).value ="";
								        	}
								    	}
									</script>


									<div class="ticks" style="padding-left: <?php echo($dados["escala_min"]*80); ?>px; width: <?php echo(($dados["escala_max"]-$dados["escala_min"])*80-50); ?>px">
										<span class="tick"></span>
										<span class="tick"></span>
									</div>
									<div class="afterticks" style="padding-left: <?php echo($dados["escala_min"]*80+85); ?>px; width: <?php echo(($dados["escala_max"]-$dados["escala_min"])*80-10); ?>px; margin-top: 5px">
										<span class="aftertick"><?php echo utf8_encode($dados["escala_baixo"]); ?></span>
										<span class="aftertick"><?php echo utf8_encode($dados["escala_alto"]); ?></span>
									</div>
									<span id="resultado"></span>

							<?php } ?>
									<br>
									<input type="submit" id="botao" value="Confirmar">
								</form>
							
						</div>
				</ul>
				</div>
					
			</div>
		</article>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
	mysqli_close($conecta);
?>