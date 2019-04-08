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
	
	$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;

	// Armazenar respostas anteriores
	if (isset($_POST["amostra{$_SESSION["amostras"][0]}"])) {
	
		foreach ($_SESSION["amostras"] as $amostra) {

			$nota = $_POST["amostra{$amostra}"];

			$consulta_resultados = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$amostra}' AND atributo_id = {$_SESSION["atributo_id"]}";
			$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
			$resultados = mysqli_fetch_assoc($acesso_resultados);

			$consulta = "SELECT * FROM atributos WHERE atributo_id = {$_SESSION["atributo_id"]}";
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);
			$atributo_completo_eng = $dados["atributo_completo_eng"];
			$atributo_completo_port = $dados["atributo_completo_port"];

			if (empty($resultados)) {
				$inserir = "INSERT INTO tb_resultados (projeto_id, formulario_id, sessao, user_id, amostra_codigo, atributo_id, atributo_completo_eng, atributo_completo_port, nota, teste) VALUES ({$_SESSION["projeto_id"]}, {$_SESSION["formulario_id"]}, {$_SESSION["sessao"]}, {$_SESSION["user_id"]}, '{$amostra}', {$_SESSION["atributo_id"]}, '{$atributo_completo_eng}', '{$atributo_completo_port}', $nota, {$_SESSION["teste"]})";

				$operacao_inserir = mysqli_query($conecta, $inserir);
			} else {

				$alterar = "UPDATE tb_resultados SET nota = {$nota}, atributo_completo_eng = '{$atributo_completo_eng}', atributo_completo_port = '{$atributo_completo_port}' WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$amostra}' AND atributo_id = {$_SESSION["atributo_id"]}";

				$operacao_alterar = mysqli_query($conecta, $alterar);
			}
		}

		if ($_SESSION["correcao"] == 1) {
			header("location:{$caminho}public/principal.php?funcao={$_SESSION["tipo_avaliador"]}&teste={$_SESSION["teste"]}");
		}
	}

	$atributos_id = array_keys($_SESSION["atributos_id"], "Aparência");
	$atributo_id = $atributos_id[0];
	$preenchido=1;
	while ($preenchido==1) {
		$consulta_resultados = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND atributo_id = $atributo_id";
	
		$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
		$n_resultados = mysqli_num_rows($acesso_resultados);
		
		if ($n_resultados!=count($_SESSION["amostras"])) {
			$preenchido=0;
			$_SESSION["atributo_id"] = $atributo_id;
		}

		$atributo_id = next($atributos_id);

		if (empty($atributo_id)) {
			$atributo_id=0;
		}
	}

	if (isset($_GET["atributo"])) {
		$_SESSION["atributo_id"] = $_GET["atributo"];
	}

	if (empty($_SESSION["atributo_id"])) {
		if ($_SESSION["first"] == 1) {
			header("location:cabines.php?first=0");
		} else {
			$_SESSION["correcao"] = 0;
			header("location:principal.php");
		}
	} 

	// Próximas variáveis
	$consulta = "SELECT * FROM atributos WHERE atributo_id = {$_SESSION["atributo_id"]}";
	$acesso = mysqli_query($conecta, $consulta);
	$dados = mysqli_fetch_assoc($acesso);


	$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$_SESSION["atributo_id"]}";
	$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes);

	$textos = array();
	$referencias = array();
	while ($linha=mysqli_fetch_assoc($acesso_opcoes)) {
		$textos[$linha["escala"]] = $linha["texto"];
		$referencias[$linha["escala"]] = $linha["referencia"];
	}
	
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

						<div style="position: relative; width: <?php echo((max(array_keys($textos))-min(array_keys($textos)))*80+90); ?>px; margin-bottom: 70px; margin-left: <?php echo(min(array_keys($textos))*80-15); ?>px; float: center;">
							<div style="position: absolute; left: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($referencias[min(array_keys($referencias))]); ?></p>
							</div>
							<div style="position: absolute; right: 0px; width: 150px">
								<p style="font-weight: bold; color: #8B0000; text-align: center; font-size: 85%; font-family: serif;">Referência:</p>
								<p style="text-align: center; font-size: 85%; margin-top: -5px; font-family: serif;"><?php echo utf8_encode($referencias[max(array_keys($referencias))]); ?></p>
							</div>
						</div>
						
						<div class="reguas" style="margin-left: -10px">

							<form action="aparencia.php" method="post" align="">
							<?php foreach ($_SESSION["amostras"] as $amostra) { ?>

								<p class="amostra"><?php echo $amostra; ?></p>
								
									<input type="range" id="nota<?php echo $amostra; ?>" name="nota<?php echo $amostra; ?>" min="0" max="10" value="0" step="0.01" style="margin-bottom: 20px;" required>
									<input type="text" id="amostra<?php echo $amostra; ?>" name="amostra<?php echo $amostra; ?>" style="width: 30px; margin-left: 5px; border: none; text-align: center; color: #FFF;">
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


									<div class="ticks" style="padding-left: <?php echo(min(array_keys($textos))*80); ?>px; width: <?php echo((number_format(max(array_keys($textos))-min(array_keys($textos))))*80-50); ?>px">
										<span class="tick"></span>
										<span class="tick"></span>
									</div>
									<div class="afterticks" style="padding-left: <?php echo(min(array_keys($textos))*80+85); ?>px; width: <?php echo number_format((max(array_keys($textos))-min(array_keys($textos)))*80-10); ?>px; margin-top: 5px">
										<span class="aftertick"><?php echo utf8_encode($textos[min(array_keys($textos))]); ?></span>
										<span class="aftertick"><?php echo utf8_encode($textos[max(array_keys($textos))]); ?></span>
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