<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");
		

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
					
					<form action="<?php echo $caminho; ?>public/avaliacao/livre.php" method="post">
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