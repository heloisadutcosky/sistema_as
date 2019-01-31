<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();
	
	if(isset($_SESSION["usuario"])) {

		$funcao = $_SESSION["funcao"] == "Administrador" ? $_GET["funcao"] : $_SESSION["funcao"];

		$consulta = "SELECT * FROM projetos WHERE form_ativo = 1 AND tipo_avaliador = '{$funcao}'";
		$acesso = mysqli_query($conecta, $consulta);
		$rows = mysqli_num_rows($acesso);

		if ($rows == 1) {
			$dados = mysqli_fetch_assoc($acesso);
			$_SESSION["produto"] = $dados["produto"];
			header("location:" . strtolower($funcao) . "/principal.php?codigo={$dados["projeto_id"]}");
		}

	} else {
			header("location:<?php echo($caminho); ?>login.php");
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

		<article>
			<p>Muito bem vindo(a), <?php echo utf8_encode($_SESSION["usuario"]); ?>! <u>Qual produto você vai avaliar hoje?</u></p>
		</article>

		<nav>
			<ul>
			<?php while($linha = mysqli_fetch_assoc($acesso)) { ?>
				<li class="menu"><a href="<?php echo strtolower($funcao); ?>/principal.php?codigo=<?php echo $linha["projeto_id"]; ?>&produto_id=<?php echo $linha["produto_id"]; ?>"><?php echo $linha["produto"]; ?></a></li>
			<?php } ?>
			</ul>
		</nav>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>