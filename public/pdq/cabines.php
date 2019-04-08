<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	

	if (isset($_GET["first"])) {
		$_SESSION["first"] = $_GET["first"];
	}

	$_SESSION["atributo_id"] = !empty($_SESSION["atributo_id"]) ? $_SESSION["atributo_id"] : 0;

			
	if (isset($_POST["atributo" . $_SESSION["atributo_id"][0]])) {

		foreach ($_SESSION["atributo_id"] as $atributo_id) {

			$nota = $_POST["atributo" . $atributo_id];

			$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);
			$atributo_completo_eng = $dados["atributo_completo_eng"];
			$atributo_completo_port = $dados["atributo_completo_port"];

			$consulta_resultados = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id};";
			$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
			$resultados = mysqli_fetch_assoc($acesso_resultados);

			if (empty($resultados)) {
				$inserir = "INSERT INTO tb_resultados (projeto_id, formulario_id, sessao, user_id, amostra_codigo, atributo_id, atributo_completo_eng, atributo_completo_port, nota, teste) VALUES ({$_SESSION["projeto_id"]}, {$_SESSION["formulario_id"]}, {$_SESSION["sessao"]}, {$_SESSION["user_id"]}, '{$_SESSION["amostra"]}', {$atributo_id}, '{$atributo_completo_eng}', '{$atributo_completo_port}', {$nota}, {$_SESSION["teste"]})";

				$operacao_inserir = mysqli_query($conecta, $inserir);
			} else {

				$alterar = "UPDATE tb_resultados SET nota = {$nota}, atributo_completo_eng = '{$atributo_completo_eng}', atributo_completo_port = '{$atributo_completo_port}' WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id}";

				$operacao_alterar = mysqli_query($conecta, $alterar);
			}
			
		}

		if ($_SESSION["correcao"] == 1) {
			header("location:{$caminho}public/principal.php?funcao={$_SESSION["tipo_avaliador"]}&teste={$_SESSION["teste"]}");
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
			$consulta = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND atributo_id = '{$atributo_id}' AND amostra_codigo = '{$_SESSION["amostra"]}'";
			
			$acesso = mysqli_query($conecta, $consulta);
			$n_resultados = mysqli_num_rows($acesso);

			if ($n_resultados == 0) {
				$preenchido=0;

				$atributo_id = !empty($atributo_id) ? $atributo_id : 0;

				$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
				$acesso = mysqli_query($conecta, $consulta);
				$dados = mysqli_fetch_assoc($acesso);

				$_SESSION["conjunto_atributos"] = $dados["conjunto_atributos"];
				$_SESSION["atributo_id"] = array_keys($_SESSION["atributos_id"], $_SESSION["conjunto_atributos"]);
				$_SESSION["n_atributos"] = count($_SESSION["atributo_id"]);
				$descricao_conjunto = utf8_encode($dados["descricao_conjunto"]);
			}

			$atributo_id = next($atributos_id);
			mysqli_free_result($acesso);
		}
	} else {
		$_SESSION["conjunto_atributos"] = "";
		if ($_SESSION["correcao"] == 1 && isset($_GET["conjunto"])) {

			$_SESSION["conjunto_atributos"] = utf8_decode($_GET["conjunto"]);

			$consulta = "SELECT * FROM atributos WHERE conjunto_atributos = '{$_SESSION["conjunto_atributos"]}' AND formulario_id = {$_SESSION["formulario_id"]}";
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);

			$_SESSION["atributo_id"] = array_keys($_SESSION["atributos_id"], $_SESSION["conjunto_atributos"]);
			$_SESSION["n_atributos"] = count($_SESSION["atributo_id"]);
			$descricao_conjunto = utf8_encode($dados["descricao_conjunto"]);

			$_SESSION["amostra"] = $_GET["amostra"];
		}
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

				<article style="margin-left: 10px">
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
	$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND conjunto_atributos = '{$_SESSION["conjunto_atributos"]}'";
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

		#botao {
		  text-decoration: none;
		  background-color: #FFF;
		  margin-left: 20px;
		  margin-bottom: 1px;
		  padding: 5px 15px;
		  color: #778899;;
		  border: 1px solid #696969;
		  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
		  font-size: 100%;
		  width: 200px;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<div style="margin-left: 10px">
			<h2 style="margin-bottom: 5px;"><?php echo $_SESSION["produto"]; ?></h2>
			
			<div style="margin-top: 40px;">
				<h3 style="font-size: 120%; color: #8B0000;"><?php echo utf8_encode($_SESSION["conjunto_atributos"]); ?></h3>
				
				<p><?php echo $descricao_conjunto; ?></p><br>

				<p class="amostra"><?php echo "Amostra " . $_SESSION["amostra"]; ?></p>
				</div>

					<?php 
					
					while($linhas=mysqli_fetch_assoc($acesso)) { 

						$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$linhas["atributo_id"]}";
						$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes);

						$textos = array();
						$referencias = array();
						while ($dados=mysqli_fetch_assoc($acesso_opcoes)) {
							$textos[$dados["escala"]] = $dados["texto"];
							$referencias[$dados["escala"]] = $dados["referencia"];
						} ?>

						<br>
						<div style="background-color: #F8F8F8; padding-left: 40px; padding-top: 20px; margin-right: 10px">
						<li style="font-size: 110%; float: left; margin-right: 10px; list-style: circle;"><b><?php echo utf8_encode($linhas["atributo"]); ?>: </b></li>
						<p style="font-size: 100%; font-family: serif; margin-top: 1px">(<?php echo utf8_encode($linhas["definicao_atributo"]); ?>)</p>

						
						<div style="position: relative; left: <?php echo(number_format(min(array_keys($textos)))*80-45); ?>px; width: <?php echo(number_format((max(array_keys($textos))-min(array_keys($textos))))*80+95); ?>px; margin-bottom: 80px; margin-top: 10px">
							<div style="position: absolute; left: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($referencias[min(array_keys($referencias))]); ?></p>
							</div>
							<div style="position: absolute; right: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($referencias[max(array_keys($referencias))]); ?></p>
							</div>
						</div>

						<form action="cabines.php" method="post" align="">
							<input type="range" id="nota<?php echo $linhas["atributo_id"]; ?>" name="atributo<?php echo $linhas["atributo_id"]; ?>" min="0" max="10" value="0" step="0.01" style="margin-bottom: 20px; margin-left: 30px" required>
							<input type="text" id="atributo<?php echo $linhas["atributo_id"]; ?>" name="atributo<?php echo $linhas["atributo_id"]; ?>" style="width: 30px; margin-left: 5px; border: none; text-align: center; background-color: #F8F8F8; color: #F8F8F8;">
							<input id="<?php echo $linhas["atributo_id"]; ?>" type="checkbox" name="teste" required style="width: 20px; float: right; margin-right: 25px; margin-top: 22px" onclick="ShowHideDiv(this.id)">
							<script type="text/javascript">
									function ShowHideDiv(clickedId) {
							        	var travar = document.getElementById(clickedId).checked ? 1 : 0;
							        	if (travar == 1) {
							        		var nota = document.getElementById("nota".concat(clickedId)).value*10;
							        		document.getElementById("nota".concat(clickedId)).disabled = true;
							        		document.getElementById("atributo".concat(clickedId)).value = nota.toFixed(1);
							        	} 
							        	else {
							        		document.getElementById("nota".concat(clickedId)).disabled = false;
							        		document.getElementById("atributo".concat(clickedId)).value = "";
							        	}
							    	}
							</script>
							<div class="ticks" style="padding-left: <?php echo(min(array_keys($textos))*80+30); ?>px; width: <?php echo((max(array_keys($textos))-min(array_keys($textos)))*80-50); ?>px">
								<span class="tick"></span>
								<span class="tick"></span>
							</div>
							<div class="afterticks" style="padding-left: <?php echo(min(array_keys($textos))*80+55); ?>px; width: <?php echo number_format((max(array_keys($textos))-min(array_keys($textos)))*80-20); ?>px; margin-top: 5px">
									<span class="aftertick"><?php echo utf8_encode($textos[min(array_keys($textos))]); ?></span>
									<span class="aftertick"><?php echo utf8_encode($textos[max(array_keys($textos))]); ?></span>
							</div>
							<span id="resultado"></span>
							</div>
							<br><br>
					<?php } ?>
							<input type="hidden" name="amostra" value="<?php echo $_SESSION["amostra"];?>">
							<input type="submit" id="botao" value="Confirmar" style="margin-left: 5px">
							<br>
						</form><br><br>
				
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
mysqli_close($conecta);
?>