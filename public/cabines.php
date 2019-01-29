<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	if (isset($_GET["first"])) {
		$_SESSION["first"] = $_GET["first"];
	}

	$projeto_id = $_SESSION["projeto_id"];
	$sessao = $_SESSION["sessao"];


	if(isset($_SESSION["usuario"])) {
		$user_id = $_SESSION["user_id"];
	} else {
		Header("Location:../login.php");
	}

	if (isset($_GET["amostra"]))	 {

		$amostra = $_GET["amostra"];

		if (isset($_POST[$_SESSION["atributo_completo"][0]])) {

			for ($i=0; $i < $_SESSION["n_atributos"]; $i++) {

				$atributo_completo = $_SESSION["atributo_completo"][$i];
				$nota = $_POST[$_SESSION["atributo_completo"][$i]]*10;

				$inserir = "INSERT INTO resultados (projeto_id, sessao, user_id, amostra_codigo, atributo_completo, nota) VALUES ($projeto_id, $sessao, $user_id, '$amostra', '$atributo_completo', $nota)";

				$operacao_inserir = mysqli_query($conecta, $inserir);
				}
		}
	}

	// #######################################################################################################################

	$conjunto_atributos = 'Aparencia';

	// Abrir consulta ao banco de dados para checar quais são os conjuntos -----------------------------------------------
	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND conjunto_atributos <> '{$conjunto_atributos}'";
	$acesso = mysqli_query($conecta, $consulta);
	// --------------------------------------------------------------------------------------------------------------------

	// Checar conjuntos de atributos
	$conjuntos_atributos = array();
	$descricao_conjuntos = array();
	for ($i=0; $i < mysqli_num_rows($acesso); $i++) {
		$row = mysqli_fetch_assoc($acesso);
		$tabela[] = $row;
		$conjuntos_atributos[] = $row["conjunto_atributos"];
		$descricao_conjuntos[] = $row["descricao_conjunto"];
	}
	$conjuntos_atributos = array_values(array_unique($conjuntos_atributos));
	$descricao_conjuntos = array_values(array_unique($descricao_conjuntos));
	$n_conjuntos = count($conjuntos_atributos);
	// --------------------------------------------------------------------------------------------------------------------


	// Ler dados para sessão atual ###############################################################################################

	$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 0;
	$n = isset($_GET["n"]) ? $_GET["n"] : -1;

	if ($n == count($_SESSION["amostras"]) - 1 && $pagina == $n_conjuntos) {
			if ($_SESSION["first"] == 1) {
				header("location:aparencia.php?first=0");
			} else {
				header("location:../logout.php");
			}
		}

	if ($pagina == $n_conjuntos || ($pagina == 0 && $n==-1)) {
		$pagina = 0;
		$n = $n+1;

		?>

		<!DOCTYPE html>
		<html lang="pt-BR">
		<head>
			<title>PDQ - Cabines</title>
			<meta charset="utf-8">
			
			<link rel="stylesheet" type="text/css" href="../_css/estilo.css">
			
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
				<?php include_once("../_incluir/topo.php"); ?>

				<article>
					<h2>PDQ - <?php echo $_SESSION["produto"]; ?></h2>
					<br>
					<h3 style="color: #8B0000">CABINES</h3>

					<p>Favor solicitar à atendente a amostra <b class="amostra"><?php echo $_SESSION["amostras"][$n]; ?></b></p>
					<br>

					<div class="botao" style="margin-left: -20px">
						<a href="cabines.php?pagina=0&n=<?php echo($n); ?>">Continuar</a>
					</div>
				</article>
				<br>
				<br>

				<?php include_once("../_incluir/rodape.php"); ?>

			</main>
		</body>
		</html>
		
	<?php } else {

	$_SESSION["conjunto_atributos"] = $conjuntos_atributos[$pagina];
	$_SESSION["descricao_conjuntos"] = $descricao_conjuntos[$pagina];
	
	// Reabrir consulta ao banco de dados - agora por conjunto
	mysqli_free_result($acesso);

	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$projeto_id} AND conjunto_atributos = '{$_SESSION["conjunto_atributos"]}'";
	$acesso = mysqli_query($conecta, $consulta);

	$_SESSION["n_atributos"] = mysqli_num_rows($acesso);
	
	// #######################################################################################################################


	###############################################################################################
	###############################################################################################
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>PDQ - Cabines</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="../_css/estilo.css">
	
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
		<?php include_once("../_incluir/topo.php"); ?>
		<h2>PDQ - <?php echo $_SESSION["produto"]; ?></h2>
		
		<br>	
		<article>
			
			<div>
				<h3><?php echo strtoupper(utf8_encode($_SESSION["conjunto_atributos"])); ?></h3>
				
				<p><?php echo utf8_encode($_SESSION["descricao_conjuntos"]); ?></p><br>

				<p class="amostra"><?php echo "Amostra " . $_SESSION["amostras"][$n]; ?></p>

				<ul type="circle">

					<?php 
					$_SESSION["atributo_completo"] = array();
					while($linhas=mysqli_fetch_assoc($acesso)) { 
						$_SESSION["atributo_completo"][] = $linhas["atributo_completo"];
						?>					

						<li><b><?php echo utf8_encode($linhas["atributo"]); ?></b></li>

						<form action="cabines.php?pagina=<?php echo($pagina + 1); ?>&n=<?php echo($n); ?>&amostra=<?php echo($_SESSION["amostras"][$n]); ?>" method="post" align="">
							<input type="range" id="nota" name="<?php echo $linhas["atributo_completo"]; ?>" min="0" max="10" value="0" step="0.01" required>
							<input type="checkbox" name="teste" required>
							<div class="ticks" style="padding-left: <?php echo($linhas["escala_min"]*60); ?>px; width: <?php echo(($linhas["escala_max"]-$linhas["escala_min"])*60); ?>px">
								<span class="tick"></span>
								<span class="tick"></span>
							</div>
							<div class="afterticks" style="padding-left: <?php echo($linhas["escala_min"]*60+25); ?>px; width: <?php echo(($linhas["escala_max"]-$linhas["escala_min"])*60+25); ?>px">
								<span class="aftertick"><?php echo utf8_encode($linhas["escala_baixo"]); ?></span>
								<span class="aftertick"><?php echo utf8_encode($linhas["escala_alto"]); ?></span>
							</div>
							<span id="resultado"></span>

					<?php } ?>
							
							<input type="submit" id="botao" value="Confirmar">
						</form>
				</ul>
			</div>
		</article>

		<?php include_once("../_incluir/rodape.php"); ?>
		
	</main>
</body>
</html>

<?php } ?>