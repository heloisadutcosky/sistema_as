<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");


	if (isset($_SESSION["amostras"]) && isset($_SESSION["n_amostra"])) {

			$_SESSION["pagina"] = $_SESSION["pagina"]+1;
			//echo "pagina = " . $_SESSION["pagina"] . " \n ";
			//echo "lista_amostra_associada = " . count($_SESSION["amostra_associada"]) . " \n";

			if ($_SESSION["pagina"] >= count($_SESSION["amostra_associada"])) {

				$_SESSION["n_amostra"] = $_SESSION["n_amostra"] + 1;
				//echo "amostra = " . $_SESSION["n_amostra"] . " \n";
				//echo count($_SESSION["amostras"]);

				if ($_SESSION["n_amostra"] >= count($_SESSION["amostras"])) {
					$_SESSION["n_amostra"] = 0;
					$_SESSION["pagina"] = -1;
					$_SESSION["paginas"] = array();
					$_SESSION["sem_amostra_associada"] = array();
					$_SESSION["amostra_associada"] = array();
					$_SESSION["formularios_ids"] = array();
					$_SESSION["tipo_avaliacao"] = array();
					header("location:{$caminho}public/principal.php");
				} else {
					$_SESSION["pagina"] = 0;
					
					$_SESSION["amostra"] = $_SESSION["amostras"][$_SESSION["n_amostra"]];
					
					//header("location:{$caminho}public/amostra.php");
				}
			
			} 	

			$pagina = $_SESSION["amostra_associada"][$_SESSION["pagina"]];
			//echo $pagina;


			$_SESSION["formulario_id"] = $_SESSION["formularios_ids"][$pagina];

			//echo $_SESSION["formulario_id"];

			$tipo_avaliacao = $_SESSION["tipo_avaliacao"][$pagina];
			//echo $tipo_avaliacao;

			if ($_SESSION["pagina"]<>0) {
				
				if (!empty($tipo_avaliacao)) {
					header("location:{$caminho}public/avaliacao/{$tipo_avaliacao}.php");
				} else {
					$_SESSION["pagina"] = -1;
					header("location:{$caminho}public/principal.php");
				}
				
			}
	}
		

	// ##########################################################################################################################
?>

		<!DOCTYPE html>
		<html lang="pt-BR">
		<head>
			<title>Amostra</title>
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
					<h3 style="color: #8B0000"></h3>

					<p>Agora você avaliará a amostra <b class="amostra"><?php echo $_SESSION["amostra"]; ?></b></p>
					<br>
					
					<form action="<?php echo $caminho; ?>public/avaliacao/<?php echo $tipo_avaliacao; ?>.php" method="post">
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
		
<?php 
	// Fechar conexão
mysqli_close($conecta);
?>