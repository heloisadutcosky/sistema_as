<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	if(isset($_SESSION["usuario"])) {
		} else {
			Header("Location:<?php echo($caminho); ?>login.php");
		}

	$_SESSION["produto"] = isset($_GET["produto"]) ? $_GET["produto"] : $_SESSION["produto"];

	if (isset($_GET["codigo"])) {
		$_SESSION["projeto_id"] = $_GET["codigo"];

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $_SESSION["projeto_id"];
		$acesso = mysqli_query($conecta, $consulta);

		$sessoes = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			$sessoes[] = $linha["sessao"];
		}

		$sessoes = array_values(array_unique($sessoes));

	} else {
		header("location:questionarios.php");
	}
	
	if (isset($_GET["sessao"])) { 
		$_SESSION["sessao"] = $_GET["sessao"];

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $_SESSION["projeto_id"] . " AND sessao = " . $_SESSION["sessao"];
		$acesso = mysqli_query($conecta, $consulta);

		$amostras = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			$amostras[] = $linha["amostra_codigo"];
		}

		shuffle($amostras);

		$_SESSION["amostras"] = $amostras;				
	} else {
		$_SESSION["sessao"] = 0;
	}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2>PDQ - <?php echo $_SESSION["produto"]; ?></h2>

		<article>
			<p>Qual sessão você vai realizar hoje?</p>
		</article>

		<nav>
			<ul>
				<?php foreach ($sessoes as $sessao) { ?>
					<li class="menu"><a href="sessoes.php?codigo=<?php echo $_SESSION["projeto_id"]; ?>&sessao=<?php echo $sessao; ?>">Sessão <?php echo $sessao; ?></a></li>
				<?php } ?>
			</ul>
		</nav>
		<br>


		<?php if ($_SESSION["sessao"] <> 0) { ?>
			<article>
				<p>E por onde você vai começar?</p>
			</article>
				
			<nav>
				<ul>
					<li class="menu"><a href="aparencia.php?sessao=<?php echo $_SESSION["sessao"]; ?>&first=1">Aparência</a></li>
					<li class="menu"><a href="cabines.php?sessao=<?php echo $_SESSION["sessao"]; ?>&first=1">Cabines</a></li>
				</ul>
			</nav>
			<br>
		<?php } ?>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>