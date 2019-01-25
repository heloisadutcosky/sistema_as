<?php 
	// Iniciar sessão
	session_start();
	
	if(isset($_SESSION["usuario"])) {
		} else {
			Header("Location: login.php");
		}

	$_SESSION["sessao"] = isset($_GET["sessao"]) ? $_GET["sessao"] : 0;

	if ($_SESSION["sessao"] <> 0) {
		$amostras = array(array(147, 982, 465),array(293, 521, 678))[$_SESSION["sessao"]-1];
		shuffle($amostras);

		$_SESSION["amostras"] = $amostras;
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once("_incluir/topo.php"); ?>

		<article>
			<p>Muito bem vindo(a), <?php echo $_SESSION["usuario"]; ?>! <u>Que sessão você vai realizar hoje?</u></p>
		</article>

		<nav>
			<ul>
				<li class="menu"><a href="sessoes.php?sessao=3">Sessão 3</a></li>
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

		<?php include_once("_incluir/rodape.php"); ?>

	</main>
</body>
</html>