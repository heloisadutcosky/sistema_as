<?php 

	$caminho =  "../../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();

	if (isset($_GET["first"])) {
		$_SESSION["first"] = $_GET["first"];
	}
	
	// Variáveis

	// Variáveis constantes na sessão
	$projeto_id = $_SESSION["projeto_id"];
	$conjunto_atributos = 'Aparencia';


	if(isset($_SESSION["usuario"])) {

		$user_id = $_SESSION["user_id"];

	} else {
		Header("Location:" . $caminho . "login.php");
	}


	$sessao = $_SESSION["sessao"];


	// Abrir consulta ao banco de dados
	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND conjunto_atributos = '{$conjunto_atributos}'";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	// Armazenar respostas anteriores
	$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
	
	
	for ($i=1; $i < $pagina; $i++) {
		$dados = mysqli_fetch_assoc($acesso);
	}
	

	if ($_SESSION["teste"] == 0) {
		foreach ($_SESSION["amostras"] as $amostra) {
			if (isset($_POST["$amostra"])) {

				$conjunto_atributos = utf8_decode($dados["conjunto_atributos"]);
				$atributo = $dados["atributo_completo"];
				$nota = $_POST["$amostra"]*10;

				$inserir = "INSERT INTO resultados (projeto_id, sessao, user_id, amostra_codigo, atributo_completo, nota) VALUES ($projeto_id, $sessao, $user_id, '$amostra', '$atributo', $nota)";

				if (!$acesso) {
					die("Falha na insercao dos dados.");
				}

				$operacao_inserir = mysqli_query($conecta, $inserir);
			}
		}
	}

	if ($pagina > mysqli_num_rows($acesso)) {
		if ($_SESSION["first"] == 1) {
			header("location:cabines.php?first=0");
		} else {
			header("location:" . $caminho . "logout.php?mensagem=1");
		}
	} 


	// Próximas variáveis
	$dados = mysqli_fetch_assoc($acesso);

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>PDQ - Aparência</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

	<style type="text/css">
		.amostra {
			float: left;
			width: 60px;
			margin: 18px 0 0 18px;
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
		<h2>PDQ - <?php echo $_SESSION["produto"]; ?></h2>
			<!-- Título do atributo avaliado -->
			<div>
				<h3><?php echo strtoupper(utf8_encode($dados["conjunto_atributos"])); ?></h3>
				<!-- Explicação do teste -->
				<p><?php echo utf8_encode($dados["descricao_conjunto"]); ?></p><br>

				<ul type="circle">

						<li><b><?php echo utf8_encode($dados["atributo"]); ?></b></li>
						
						<div class="reguas">

							<?php foreach ($_SESSION["amostras"] as $amostra) { ?>

								<p class="amostra"><?php echo $amostra; ?></p>
								
								<form action="aparencia.php?pagina=<?php echo($pagina + 1); ?>" method="post" align="">
									<input type="range" id="nota" name="<?php echo $amostra; ?>" min="0" max="10" value="0" step="0.01" style="margin-bottom: 20px;" required>
									<input type="checkbox" name="teste" required>
									<div class="ticks" style="padding-left: <?php echo($dados["escala_min"]*60); ?>px; width: <?php echo(($dados["escala_max"]-$dados["escala_min"])*60); ?>px">
										<span class="tick"></span>
										<span class="tick"></span>
									</div>
									<div class="afterticks" style="padding-left: <?php echo($dados["escala_min"]*60+100); ?>px; width: <?php echo(($dados["escala_max"]-$dados["escala_min"])*60+40); ?>px">
										<span class="aftertick"><?php echo utf8_encode($dados["escala_baixo"]); ?></span>
										<span class="aftertick"><?php echo utf8_encode($dados["escala_alto"]); ?></span>
									</div>
									<span id="resultado"></span>

							<?php } ?>
									
									<input type="submit" id="botao" value="Confirmar">
								</form>
							
						</div>
				</ul>
					
			</div>
		</article>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>

	</main>
</body>
</html>