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

	if (isset($_POST["marca"])) {
		$marca = utf8_decode($_POST["marca"]);
		$categoria_id = $_GET["codigo"];

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------

			$consulta_marca = "SELECT * FROM marcas WHERE categoria_id = {$categoria_id} AND marca = '{$marca}'";

			$acesso = mysqli_query($conecta, $consulta_marca);
			$existe_marca = mysqli_fetch_assoc($acesso);

			if (!empty($existe_marca)) { ?>
				<p>Essa marca já foi cadastrada</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO marcas (categoria_id, marca) VALUES ({$categoria_id}, '{$marca}')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro da marca.");
				} else {
					header("location:marcas.php?codigo=" . $categoria_id . "&categoria=" . $_SESSION["categoria"]);
				}
			}
		}
	}

	if (isset($_GET["marca"])) {
		$marca = utf8_decode($_GET["marca"]);
		$categoria_id = $_GET["codigo"];

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM marcas WHERE categoria_id = {$categoria_id} AND marca = '{$marca}'";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:marcas.php?codigo=" . $categoria_id . "&categoria=" . $_SESSION["categoria"]);
			}
		}
		// --------------------------------------------------------------------------
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Produtos - Marcas</title>
	
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
			<h2 class="espaco">Marcas - <?php echo $_SESSION["categoria"]; ?></h2>
			<br>

			<?php 
				if (isset($_GET["codigo"])) {
					$categoria_id = $_GET["codigo"];

					$consulta = "SELECT * FROM marcas WHERE categoria_id = " . $categoria_id;
					$acesso = mysqli_query($conecta, $consulta); ?>

					<div style="width: 350px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 50px; margin-left: 20px">
						<form action="marcas.php?acao=cadastro&codigo=<?php echo $categoria_id ?>&categoria=<?php echo $_SESSION["categoria"] ?>" method="post">
							<div style="float: left; margin-right: 10px;">
								<label for="marca">Nova marca: </label>
								<input type="text" id="marca" name="marca" required>
							</div>

							<div style="padding-top: 15px;">
								<input type="submit" id="botao" value="Cadastrar" style="width: 100px; height: 20px; padding-top: 2px">
							</div>
						</form>
					</div>
					<br><br>

					<div id="janela" style="width: 180px">
						<?php while($linha = mysqli_fetch_assoc($acesso)) { ?>
						<ul>
						    <li style="width: 100px"><?php echo utf8_encode($linha["marca"]) ?></li>
						    <li style="width: 50px"><a href="marcas.php?acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo $_SESSION["categoria"] ?>&marca=<?php echo utf8_encode($linha["marca"]) ?>">Excluir</a> </li>
						</ul>
						<?php } ?>	
					</div>
				<?php } ?>
				<br><br><br><br>
		</article>

		<div class="direita">
			<a href="dados.php?tipo=categorias">Voltar</a><br><br>
		</div>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>