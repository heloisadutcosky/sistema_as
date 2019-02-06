<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";

	$_SESSION["categoria"] = isset($_GET["categoria"]) ? $_GET["categoria"] : "";

	if (isset($_POST["sabor"])) {
		$sabor = utf8_decode($_POST["sabor"]);
		$categoria_id = $_GET["codigo"];

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------

			$consulta_sabor = "SELECT * FROM sabores WHERE categoria_id = {$categoria_id} AND sabor = '{$sabor}'";

			$acesso = mysqli_query($conecta, $consulta_sabor);
			$existe_sabor = mysqli_fetch_assoc($acesso);

			if (!empty($existe_sabor)) { ?>
				<p>Esse sabor já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO sabores (categoria_id, sabor) VALUES ({$categoria_id}, '{$sabor}')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro da sabor.");
				} else {
					header("location:sabores.php?codigo=" . $categoria_id . "&categoria=" . $_SESSION["categoria"]);
				}
			}
		}
	}

	if (isset($_GET["sabor"])) {
		$sabor = utf8_decode($_GET["sabor"]);
		$categoria_id = $_GET["codigo"];

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM sabores WHERE categoria_id = {$categoria_id} AND sabor = '{$sabor}'";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:sabores.php?codigo=" . $categoria_id . "&categoria=" . $_SESSION["categoria"]);
			}
		}
		// --------------------------------------------------------------------------
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Produtos - Sabores</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

	<style type="text/css">
		li a {
	    list-style:none;
	    display:inline-block;
	    background-color: none;	    
	}

	div#janela li:nth-child(2) a {
	    color:#9A9668;
	    text-align:center;
	    padding:0 10px;
	}

	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco">Sabores - <?php echo $_SESSION["categoria"]; ?></h2>
			<br>

			<?php 
				if (isset($_GET["codigo"])) {
					$categoria_id = $_GET["codigo"];

					$consulta = "SELECT * FROM sabores WHERE categoria_id = " . $categoria_id;
					$acesso = mysqli_query($conecta, $consulta); ?>

					<div style="width: 350px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 50px; margin-left: 20px">
						<form action="sabores.php?acao=cadastro&codigo=<?php echo $categoria_id ?>&categoria=<?php echo $_SESSION["categoria"] ?>" method="post">
							<div style="float: left; margin-right: 10px;">
								<label for="sabor">Novo sabor: </label>
								<input type="text" id="sabor" name="sabor" required>
							</div>

							<div style="padding-top: 15px;">
								<input type="submit" id="botao" value="Cadastrar" style="width: 100px; height: 20px; padding-top: 2px;">
							</div>
						</form>
					</div>
					<br>

					<div id="janela" style="width: 180px">
						<?php while($linha = mysqli_fetch_assoc($acesso)) { ?>
						<ul>
						    <li style="width: 100px"><?php echo utf8_encode($linha["sabor"]) ?></li>
						    <li style="width: 50px"><a href="sabores.php?acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo $_SESSION["categoria"] ?>&sabor=<?php echo utf8_encode($linha["sabor"]) ?>">Excluir</a> </li>
						</ul>
						<?php } ?>	
					</div>
				<?php } ?>
				<br><br><br><br><br><br>
		</article>

		<div class="direita">
			<a href="dados.php?tipo=categorias">Voltar</a><br><br>
		</div>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>