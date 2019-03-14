<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$_SESSION["produto"] = isset($_GET["produto"]) ? $_GET["produto"] : $_SESSION["produto"];
	$_SESSION["correcao"] = isset($_GET["corrigir"]) ? $_GET["corrigir"] : 0;


	// Já pegar todas as informações do projeto
	if (isset($_SESSION["projeto_id"])) {

		$consulta = "SELECT * FROM tb_amostras WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]}";
		$acesso = mysqli_query($conecta, $consulta);

		$amostras = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			$amostras[$linha["amostra_codigo"]] = $linha["sessao"];
		}
		$sessoes = array_values(array_unique(array_values($amostras)));

		// Abrir consulta ao banco de dados para checar quais são os conjuntos -----------------------------------------------
		$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
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
	} else {
		$_SESSION["sessao"] = 0;
	}

	if (isset($_GET["amostra"])) { 
		$_SESSION["amostra"] = $_GET["amostra"];
	} else {
		$_SESSION["amostra"] = 0;
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
		.menu2 {
			display: inline-block;
		}
		li.menu2 a {
		  text-decoration: none;
		  background-color: #FFF;
		  margin-bottom: 1px;
		  padding: 2px 12px;
		  color: #C2534B;
		  border: 1px solid #B8B8B8;
		}

		li.menu2 a:hover {
		  background-color: #F99B95;
		  margin: 0 auto;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		

		<?php 
		$consulta = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND user_id = {$_SESSION["user_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		if ((mysqli_num_rows($acesso) != count($amostras)*$n_atributos) && $_SESSION["correcao"]==0) { ?>

			<article style="margin-left: 10px">
				<h2><?php echo $_SESSION["produto"]; ?></h2><br>
				<p style="margin-bottom: 30px">Qual sessão você vai realizar hoje?</p>
			

				<nav>
					<ul>
						<?php foreach ($sessoes as $sessao) { 
								
								$consulta = "SELECT * FROM resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$sessao} AND user_id = {$_SESSION["user_id"]}";
								$acesso = mysqli_query($conecta, $consulta);
								if (mysqli_num_rows($acesso) != count(array_keys($amostras, $sessao))*$n_atributos) { ?>
									<li class="menu"><a href="principal.php?sessao=<?php echo $sessao; ?>" style="<?php if ($sessao == $_SESSION["sessao"]) {echo "background-color: #F99B95"; }?>">Sessão <?php echo $sessao; ?></a></li>
								<?php } ?>
						<?php } ?>
					</ul>
				</nav>
				<br><br>


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
		<?php } else if ($_SESSION["correcao"] == 1) { ?>

			<article style="margin-left: 10px">
				<h2><?php echo $_SESSION["produto"]; ?></h2><br>
				<p style="margin-bottom: -20px">Em qual amostra você deseja retornar?</p>
			

				<nav>
					<ul>
						<?php foreach ($sessoes as $sessao) { ?>
							<br><br>
							<li class="menu"><a>Sessão <?php echo $sessao; ?></a></li>
							<?php foreach (array_keys($amostras, $sessao) as $amostra) { ?>
								<li class="menu2"><a href="principal.php?sessao=<?php echo $sessao; ?>&amostra=<?php echo $amostra; ?>&corrigir=1" style="color: #8B0000; font-size: 105%; <?php if ($amostra == $_SESSION["amostra"]) {echo "background-color: #F99B95"; }?>"><b><?php echo $amostra; ?></b></a></li>
							<?php } ?>
						<?php } ?>
					</ul>
				</nav>
				<br><br>

				<?php if ($_SESSION["amostra"] <> 0) { ?>
						<p>Quais atributos você deseja reavaliar?</p>
						
					<nav>
						<ul style="display: inline-block;">
							<li class="menu"><a>Aparência</a></li>
							<?php 
							foreach (array_keys($_SESSION["atributos_id"], "Aparência") as $atributo_id) { 
								$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
								$acesso = mysqli_query($conecta, $consulta);
								$linha = mysqli_fetch_assoc($acesso);
								?>
								<li class="menu2"><a href="aparencia.php?&atributo=<?php echo $linha["atributo_id"]; ?>" style="color: #8B0000;"><?php echo utf8_encode($linha["atributo"]); ?></a></li>
							<?php } ?>

							<br><br>
							<?php 
							$conjunto_anterior = "Aparência";
							foreach ($_SESSION["atributos_id"] as $conjunto) { 
								if ($conjunto != $conjunto_anterior) { ?>
									<li class="menu"><a href="cabines.php?amostra=<?php echo $_SESSION["amostra"]; ?>&conjunto=<?php echo $conjunto; ?>"><?php echo $conjunto; ?></a></li>
								<?php }
								$conjunto_anterior = $conjunto;
							} ?>
						</ul>
					</nav>
					<br>
				<?php } ?>
			</article>

		<?php } else { 
			header("location:{$caminho}public/principal.php?funcao={$_SESSION["tipo_avaliador"]}&teste={$_SESSION["teste"]}");
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