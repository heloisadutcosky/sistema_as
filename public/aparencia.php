<?php require_once("../conexao/conexao.php"); ?>

<?php 
	session_start();

	if (isset($_GET["first"])) {
		$_SESSION["first"] = $_GET["first"];
	}
	
	// Variáveis

	// Variáveis constantes na sessão
	$projeto_id = 888;
	$conjunto_atributos = 'Aparencia';


	if(isset($_SESSION["usuario"])) {

		$user_id = $_SESSION["user_id"];

	} else {
		Header("Location: login.php");
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

	if ($pagina > mysqli_num_rows($acesso)) {
		if ($_SESSION["first"] == 1) {
			header("location:cabines.php?first=0");
		} else {
			header("location:logout.php");
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

	<link rel="stylesheet" type="text/css" href="_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="_css/estilo_aparencia.css">

</head>
<body>
	<main>
		<?php include_once("_incluir/topo.php"); ?>

		<article>
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
									<input type="range" id="nota" name="<?php echo $amostra; ?>" min="0" max="10" value="0" step="0.01" required>
									<input type="checkbox" name="teste" required>
									<div class="ticks">
										<span class="tick"></span>
										<span class="tick"></span>
									</div>
									<div class="afterticks">
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

		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>