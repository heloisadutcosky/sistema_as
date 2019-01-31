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

	if (isset($_GET["produto_id"])) {
		$produto_id = $_GET["produto_id"];

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM produtos WHERE produto_id = {$produto_id}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} 
		}
		// --------------------------------------------------------------------------
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Produtos</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">

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
		<h2 class="espaco">Categorias e produtos cadastrados</h2>
		<br>

		<form action="dados.php" method="get">
			<label for="tipo">Tabela: </label>
			<select id="tipo" name="tipo"><br>
			<?php
			$tipo = isset($_GET["tipo"]) ? $_GET["tipo"] : "";
			switch ($tipo) {
				case 'produtos': ?>
					<option value="categorias">Categorias</option>
					<option value="produtos" selected>Produtos</option>
					<?php break;

				default: ?>
					<option value="categorias" selected>Categorias</option>
					<option value="produtos">Produtos</option>
					<?php break; 
				}?>
			</select>

			<input type="submit" id="botao" value="Visualizar"><br>
		</form>
		<br>
		<br>

		<?php 
			if (isset($_GET["tipo"])) {
				$tipo = $_GET["tipo"];

				if ($tipo == "categorias") { 

					$consulta = "SELECT * FROM categorias";
					$acesso = mysqli_query($conecta, $consulta); ?>

					<form action="dados.php?tipo=categorias&acao=cadastro" method="post">
						<label for="categoria">Nova categoria: </label>
						<input type="text" id="categoria" name="categoria" required>

						<input type="submit" id="botao" value="Cadastrar">
					</form>
					<br>

					<div id="cima_tabela" style="width: 400px">
						<ul>
						    <li><b>Categorias</b></li>
						</ul>
					</div>

					<div id="janela" style="width: 400px">
						<?php
						    while($linha = mysqli_fetch_assoc($acesso)) {
						?>
						<ul>
						    <li style="width: 120px"><?php echo utf8_encode($linha["categoria"]) ?></li>
						    <li style="width: 60px"><a href="marcas.php?codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo utf8_encode($linha["categoria"]) ?>">Marcas</a> </li>
						    <li style="width: 65px"><a href="sabores.php?codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo utf8_encode($linha["categoria"]) ?>">Sabores</a> </li>
						    <li style="width: 65px"><a href="produtos.php?codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo utf8_encode($linha["categoria"]) ?>">Produtos</a> </li>
						    <li style="width: 50px"><a href="dados.php?tipo=categorias&acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>">Excluir</a> </li>
						</ul>
						<?php } ?>	
					</div>
				<?php } ?>

				<?php if ($tipo == "produtos") { 
					$consulta1 = "SELECT * FROM produtos";
					$acesso1 = mysqli_query($conecta, $consulta1); ?>

					<div class="botao">
						<a class="espaco" href="cadastro_produto.php">Cadastrar produto</a><br>
					</div>
					<br>

					<div id="cima_tabela" style="width: 420px">
						<ul>
						    <li style="width: 100px"><b>Produto</b></li>
						    <li style="width: 80px"><b>Categoria</b></li>
						    <li style="width: 70px"><b>Sabor</b></li>
						    <li style="width: 70px"><b>Marca</b></li>
						</ul>
					</div>
					
					<div id="janela" style="width: 420px">
						<?php
						    while($linha = mysqli_fetch_assoc($acesso1)) {
						    	$consulta2 = "SELECT * FROM categorias WHERE categoria_id = '{$linha["categoria_id"]}'";
								$acesso2 = mysqli_query($conecta, $consulta2);
								$dados = mysqli_fetch_assoc($acesso2);
						?>
						<ul>
						    <li style="width: 100px"><?php echo utf8_encode($linha["produto"]); ?></li>
						    <li style="width: 80px"><?php echo utf8_encode($dados["categoria"]); ?></li>
						    <li style="width: 70px"><?php echo utf8_encode($linha["sabor"]); ?></li>
						    <li style="width: 70px"><?php echo utf8_encode($linha["marca"]); ?></li>
						    <li style="width: 50px"><a href="dados.php?tipo=produtos&acao=exclusao&produto_id=<?php echo $linha["produto_id"] ?>">Excluir</a>
						</ul>
						<?php } ?>	
					</div>
				<?php } ?>
		<?php } ?>	

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>

		<br>
		<br>
		<br>		

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>