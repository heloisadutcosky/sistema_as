<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$funcao = isset($_GET["funcao"]) ? $_GET["funcao"] : $_SESSION["funcao"];

	// Setar projeto e categoria
	if (isset($_GET["codigo"])) {
		$_SESSION["projeto_id"] = $_GET["codigo"];
		$_SESSION["produto_id"] = $_GET["produto_id"];

		$consulta = "SELECT * FROM produtos WHERE produto_id = {$_SESSION["produto_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);
		$_SESSION["categoria_id"] = $dados["categoria_id"];
		$_SESSION["produto"] = $dados["produto"];

		header("location:consumo.php?funcao={$funcao}");
	} 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<link rel="shortcut icon" href="/favicon.png" type="image/png">
	<link rel="shortcut icon" type="image/png" href="http://pngimg.com/uploads/wheat/wheat_PNG65.png" />
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<p>Muito bem vindo(a), <?php echo utf8_encode($_SESSION["usuario"]); ?>! <u>Qual será a avaliação que você vai realizar hoje?</u></p>
		</article>

		<nav>
			<ul style="list-style: none;">
			<?php 

				$consulta = "SELECT * FROM projetos WHERE form_ativo = 1"; 
					//AND tipo_avaliador = '{$funcao}'";
				$acesso = mysqli_query($conecta, $consulta);
				$rows = mysqli_num_rows($acesso);

				if ($rows == 1) {
					$dados = mysqli_fetch_assoc($acesso);
					$_SESSION["form"] = $dados["nome_form"];
					//header("location:" . strtolower($funcao) . "/principal.php?codigo={$dados["projeto_id"]}");
				}

				while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<img src="
					<?php 
						$consulta = "SELECT * FROM produtos WHERE produto_id = {$linha["produto_id"]}"; 
						$acesso2 = mysqli_query($conecta, $consulta);
						$dados = mysqli_fetch_assoc($acesso2);
						echo $dados["url_imagem"]; 
					?>
					" width="100" height="70" style="float: left;"><br><br>
					<li class="menu"><a href="principal.php?codigo=<?php echo $linha["projeto_id"]; ?>&produto_id=<?php echo $linha["produto_id"]; ?>&produto=<?php echo $linha["nome_form"]; ?>&funcao=<?php echo $funcao; ?>"><?php echo $linha["nome_form"]; ?></a></li><br><br>
				<?php } ?>
			</ul>
		</nav>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>