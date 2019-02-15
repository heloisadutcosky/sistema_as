<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";

	if (isset($_POST["categoria"])) {
		$categoria = utf8_decode($_POST["categoria"]);

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência da categoria na base ------------------------------
			$consulta_categoria = "SELECT * FROM categorias WHERE categoria = '{$categoria}'";

			$acesso = mysqli_query($conecta, $consulta_categoria);
			$existe_categoria = mysqli_fetch_assoc($acesso);

			if (!empty($existe_categoria)) { ?>
				<p>Essa categoria já foi cadastrada</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO categorias (categoria) VALUES ('{$categoria}')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro da marca.");
				} else {
					header("location:dados.php?tipo=categorias");
				}
			}
		}
	}

	if (isset($_GET["codigo"])) {
		$categoria_id = $_GET["codigo"];

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM categorias WHERE categoria_id = {$categoria_id}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php?tipo=categorias");
			}
		}
		// --------------------------------------------------------------------------
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Categorias</title>
	
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

	div#janela li:nth-child(2) a, div#janela li:nth-child(3) a, div#janela li:nth-child(3) a {
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
		<h2 class="espaco">Categorias cadastradas</h2>
		<br>

		<?php 
		
			$consulta = "SELECT * FROM categorias";
			$acesso = mysqli_query($conecta, $consulta); ?>

			<div style="width: 350px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 50px; margin-left: 20px">
				<form action="dados.php?tipo=categorias&acao=cadastro" method="post">
					<div style="float: left; margin-right: 10px;">
						<label for="categoria">Nova categoria: </label>
						<input type="text" id="categoria" name="categoria" required>
					</div>

					<div style="padding-top: 15px;">
						<input type="submit" id="botao" value="Cadastrar" style="width: 100px; height: 20px; padding-top: 2px;">
					</div>
				</form>
			</div>
			<br>

			<div id="cima_tabela" style="width: 355px">
				<ul>
				    <li><b>Categorias</b></li>
				</ul>
			</div>

			<div id="janela" style="width: 355px">
				<?php
				    while($linha = mysqli_fetch_assoc($acesso)) {
				?>
				<ul>
				    <li style="width: 115px"><?php echo utf8_encode($linha["categoria"]) ?></li>
				    <li style="width: 60px"><a href="classes.php?codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo utf8_encode($linha["categoria"]) ?>">Classes</a> </li>
				    <li style="width: 90px"><a href="form_consumo.php?codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo utf8_encode($linha["categoria"]) ?>">Questionário</a> </li>
				    <li style="width: 50px"><a href="dados.php?tipo=categorias&acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>">Excluir</a> </li>
				</ul>
				<?php } ?>	
			</div>
		<br><br><br><br><br><br>
		</article>	

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>	

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>