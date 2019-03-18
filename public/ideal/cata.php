<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	if (isset($_POST["completo"])) {

			$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
			$acesso = mysqli_query($conecta, $consulta);
			
			while ($dados = mysqli_fetch_assoc($acesso)) {
					
				$nota = isset($_POST["atributo{$dados["atributo_id"]}"]) ? 1 : 0;

				$atributo_id = $dados["atributo_id"];
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

		$_SESSION["formulario_id"] = array_values($_SESSION["formularios"])[array_search($_SESSION["formulario_id"], array_values($_SESSION["formularios"]))+1];
		if (!$_SESSION["formulario_id"]) {
			$_SESSION["amostra"] = $_SESSION["amostras"][array_search($_SESSION["amostra"], $_SESSION["amostras"])+1];
			echo $_SESSION["amostra"];
			header("location:amostra.php");
		} else {
			$_SESSION["tipo_formulario"] = array_keys($_SESSION["formularios"], $_SESSION["formulario_id"])[0];
			header("location:{$_SESSION["tipo_formulario"]}.php");
		}
	}
		

	// ##########################################################################################################################
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>CATA</title>
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

		li {	
		  display: inline-block;
		  text-decoration: none;
		  background-color: #F7F6F6;
		  margin: 1px;
		  padding: 5px 5px;
		  color: #626161;

		  border: 1px solid #C1B7B7;
		  width: 80px;
		  height: 60px;
		  vertical-align: middle;
		  text-decoration: none;
		  text-align: center;

		  font-size: 75%;
		  color: #626161;
		}

		li:hover {
		  background-color: #FFE1E1;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<div style="margin-left: 10px">
			<h2 style="margin-bottom: 5px;"><?php echo $_SESSION["produto"]; ?></h2>

				<p class="amostra"><?php echo "Amostra " . $_SESSION["amostra"]; ?></p><br>

						<form action="" method="post" align="">


							<?php 
									$consulta_atributos = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
									$acesso_atributos = mysqli_query($conecta, $consulta_atributos);
									$dados_atributos = mysqli_fetch_assoc($acesso_atributos)
									?>
									<p><?php echo utf8_encode($dados_atributos["descricao_conjunto"]); ?></p>
									<div style="margin-left: -10px; margin-top: 10px">
										<?php 
										$consulta_atributos = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
										$acesso_atributos = mysqli_query($conecta, $consulta_atributos);
										while ($dados_atributos = mysqli_fetch_assoc($acesso_atributos)) { ?>
											<div style="float: left; margin-left: 5px">
												<label for="<?php echo $dados_atributos["atributo_id"]; ?>" style="margin-right: 50px; margin-left: 5px; margin-bottom: 10px; float: left;">
													<input type="checkbox" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>" id="<?php echo $dados_atributos["atributo_id"]; ?>" style="width: 10px; float: left; margin-bottom: 10px;"/>
													<?php echo $dados_atributos["atributo"]; ?>
												</label><br><br>
											</div>
									<?php } ?>
							
							<br><br><br><br><br><br>
							<input type="hidden" name="amostra" value="<?php echo $_SESSION["amostra"];?>">
							<input type="submit" id="botao" value="Confirmar" name="completo" style="margin-left: 5px">
							<br>
						</form><br><br>
				
			</div>
		</article>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>
		
	</main>
</body>
</html>

<?php 
	// Fechar conexão
mysqli_close($conecta);
?>