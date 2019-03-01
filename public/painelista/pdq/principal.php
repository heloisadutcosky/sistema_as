<?php 

	$caminho =  "../../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$_SESSION["produto"] = isset($_GET["produto"]) ? $_GET["produto"] : $_SESSION["produto"];


	// Já pegar todas as informações do projeto
	if (isset($_SESSION["projeto_id"])) {

		$consulta = "SELECT * FROM amostras WHERE projeto_id = " . $_SESSION["projeto_id"];
		$acesso = mysqli_query($conecta, $consulta);

		$amostras = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			$amostras[$linha["amostra_codigo"]] = $linha["sessao"];
		}
		$sessoes = array_values(array_unique(array_values($amostras)));

		// Abrir consulta ao banco de dados para checar quais são os conjuntos -----------------------------------------------
		$consulta = "SELECT * FROM formularios WHERE projeto_id = {$_SESSION["projeto_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		// --------------------------------------------------------------------------------------------------------------------

		// Checar conjuntos de atributos
		$_SESSION["atributos_id"] = array();
		while ($row = mysqli_fetch_assoc($acesso)) {
			$_SESSION["atributos_id"][$row["atributo_id"]] = utf8_encode($row["conjunto_atributos"]);
		}

		$n_atributos = count($_SESSION["atributos_id"]);

	} else {
		header("location:../principal.php");
	}
	
	if (isset($_GET["sessao"])) { 
		$_SESSION["sessao"] = $_GET["sessao"];

		$_SESSION["amostras"] = array_keys($amostras, $_SESSION["sessao"]);
		
		shuffle($_SESSION["amostras"]);
		$_SESSION["amostra"] = 0;

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
		

		<?php 
		$consulta = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND user_id = {$_SESSION["user_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		if (mysqli_num_rows($acesso) != count($amostras)*$n_atributos) { ?>

			<article>
				<h2><?php echo $_SESSION["produto"]; ?></h2><br>
				<p>Qual sessão você vai realizar hoje?</p>
			

				<nav>
					<ul>
						<?php foreach ($sessoes as $sessao) { 
								
								$consulta = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND sessao = {$sessao} AND user_id = {$_SESSION["user_id"]}";
								$acesso = mysqli_query($conecta, $consulta);
								if (mysqli_num_rows($acesso) != count(array_keys($amostras, $sessao))*$n_atributos) { ?>
									<li class="menu"><a href="principal.php?codigo=<?php echo $_SESSION["projeto_id"]; ?>&sessao=<?php echo $sessao; ?>">Sessão <?php echo $sessao; ?></a></li>
								<?php } ?>
						<?php } ?>
					</ul>
				</nav>
				<br>


				<?php if ($_SESSION["sessao"] <> 0) { ?>
						<p>E por onde você vai começar?</p>
						
					<nav>
						<ul style="display: inline-block;">
							<li class="menu"><a href="aparencia.php?sessao=<?php echo $_SESSION["sessao"]; ?>&first=1">Aparência</a></li>
							<li class="menu"><a href="cabines.php?sessao=<?php echo $_SESSION["sessao"]; ?>&first=1">Cabines</a></li>
						</ul>
					</nav>
					<br>
				<?php } ?>
			</article>
		<?php } else { 
			header("location:{$caminho}public/principal.php?funcao={$_SESSION["funcao_temp"]}&teste={$_SESSION["teste"]}");
		 } ?>

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