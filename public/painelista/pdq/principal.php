<?php 

	$caminho =  "../../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$_SESSION["produto"] = isset($_GET["produto"]) ? $_GET["produto"] : $_SESSION["produto"];

	if (isset($_SESSION["projeto_id"])) {

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $_SESSION["projeto_id"];
		$acesso = mysqli_query($conecta, $consulta);

		$sessoes = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			$sessoes[] = $linha["sessao"];
		}

		$sessoes = array_values(array_unique($sessoes));

	} else {
		header("location:../principal.php");
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
	<style type="text/css">
		.menu {
			display: inline-block;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		

		<article>
			<h2><?php echo $_SESSION["produto"]; ?></h2><br>
			<p>Qual sessão você vai realizar hoje?</p>
		</article>

		<nav>
			<ul>
				<?php foreach ($sessoes as $sessao) { ?>
					<li class="menu"><a href="principal.php?codigo=<?php echo $_SESSION["projeto_id"]; ?>&sessao=<?php echo $sessao; ?>">Sessão <?php echo $sessao; ?></a></li>
				<?php } ?>
			</ul>
		</nav>
		<br>


		<?php if ($_SESSION["sessao"] <> 0) { ?>
			<article>
				<p>E por onde você vai começar?</p>
			</article>
				
			<nav>
				<ul style="display: inline-block;">
					<li class="menu"><a href="aparencia.php?sessao=<?php echo $_SESSION["sessao"]; ?>&first=1">Aparência</a></li>
					<li class="menu"><a href="cabines.php?sessao=<?php echo $_SESSION["sessao"]; ?>&first=1">Cabines</a></li>
				</ul>
			</nav>
			<br>
		<?php } ?>

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