<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "cadastro";
	$produto_id = 0;
	$categoria_id = 0;

	if (isset($_POST["categoria"])) {
		$categoria = utf8_decode($_POST["categoria"]);
		$url_imagem_cat = utf8_decode($_POST["url_imagem_cat"]);

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE categorias SET categoria = '{$categoria}', url_imagem = '{$url_imagem_cat}' WHERE categoria_id = {$_GET["categoria"]}";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php");
			}
		}
		// --------------------------------------------------------------------------

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
				$cadastrar = "INSERT INTO categorias (categoria, url_imagem) VALUES ('{$categoria}', '{$url_imagem_cat}')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro da marca.");
				} else {
					header("location:dados.php");
				}
			}
		}
	}

	if (isset($_GET["categoria"])) {

		if ($acao == "alteracao") {
			$categoria_id = $_GET["categoria"];
		}

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM categorias WHERE categoria_id = {$_GET["categoria"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			$excluir = "DELETE FROM produtos WHERE categoria_id = {$_GET["categoria"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				echo $excluir;
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php");
			}
		}
		// --------------------------------------------------------------------------
	}

	if (isset($_POST["produto"])) {

		echo $_POST["produto"];
		$produto = utf8_decode($_POST["produto"]);
		echo $produto;
		$url_imagem = utf8_decode($_POST["url_imagem"]);

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência da categoria na base ------------------------------
			$consulta_produto = "SELECT * FROM produtos WHERE categoria_id = {$_POST["categoria_produto"]} AND produto = '{$produto}'";

			$acesso = mysqli_query($conecta, $consulta_produto);
			$existe_produto = mysqli_fetch_assoc($acesso);

			if (!empty($existe_produto)) { ?>
				<p>Esse produto já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO produtos (categoria_id, produto, url_imagem) VALUES ({$_POST["categoria_produto"]}, '{$produto}', '{$url_imagem}')";
				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					echo $cadastrar;
					die("Falha no cadastro do produto.");
				} else {
					header("location:dados.php");
				}
			}
		}

		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {

			echo $produto;
				
			$alterar = "UPDATE produtos SET categoria_id = {$_POST["categoria_produto"]}, produto = '{$produto}', url_imagem = '{$url_imagem}' WHERE produto_id = {$_GET["produto"]}";

			echo $alterar;
			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				echo $alterar;
				die("Falha na alteração dos dados.");
			} else {
				header("location:dados.php");
			}
		}
		// --------------------------------------------------------------------------
	}

	if (isset($_GET["produto"])) {

		if ($acao == "alteracao") {
			$produto_id = $_GET["produto"];
		}

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM produtos WHERE produto_id = {$_GET["produto"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				echo $excluir;
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php");
			}
		}
		// --------------------------------------------------------------------------
	}

	$consulta2 = "SELECT * FROM produtos WHERE produto_id = {$produto_id}";
	$acesso2 = mysqli_query($conecta, $consulta2);
	$produtos = mysqli_fetch_assoc($acesso2);

	$consulta3 = "SELECT * FROM categorias WHERE categoria_id = {$categoria_id}";
	$acesso3 = mysqli_query($conecta, $consulta3);
	$categorias = mysqli_fetch_assoc($acesso3);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Produtos</title>
	
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
		<h2>CATEGORIAS E PRODUTOS CADASTRADOS</h2>

		<div style="position: static; width: 750px">
		<br>
		<div style="background-color: #F8F8F8; padding: 5px 5px 15px 5px; width: 265px; position: absolute; left: 5px">
			<h2 style="margin-left: 12px;">Categorias</h2>
			<?php 
			
				$consulta = "SELECT * FROM categorias";
				$acesso = mysqli_query($conecta, $consulta); ?>

				<div style="width: 240px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 95px; margin-left: 10px; position: relative; padding-bottom: 5px">
					<form action="dados.php?acao=<?php echo $acao; ?>&categoria=<?php echo $categoria_id; ?>" method="post">

							<div>
								<label for="categoria">Nova categoria: </label>
								<input type="text" id="categoria" name="categoria" value="<?php echo(utf8_encode($categorias["categoria"])); ?>" style="width: 120px" required>
							</div>

							<div>
							<input type="submit" id="botao" value="<?php 
								if ($acao == "alteracao") echo "Alterar";
								if ($acao == "cadastro") echo "Cadastrar";
							?>" style="width: 70px; height: 20px; padding: 2px; position: absolute; right: 7px; top: 25px">
							</div>

						<div>
							<label for="url_imagem_cat">URL Imagem: </label>
							<input type="text" id="url_imagem_cat" name="url_imagem_cat" value="<?php echo(utf8_encode($categorias["url_imagem"])); ?>" style="width: 210px">
						</div>
					</form>
				</div>
				<br>

				<div id="cima_tabela" style="width: 240px; margin-left: 10px">
					<ul>
					    <li><b>Categoria</b></li>
					</ul>
				</div>

				<div id="janela" style="width: 240px; margin-left: 10px">
					<?php
					    while($linha = mysqli_fetch_assoc($acesso)) {
					?>
					<ul>
					    <li style="width: 110px"><?php echo utf8_encode($linha["categoria"]) ?></li>
					    <li style="width: 50px"><a href="dados.php?acao=alteracao&categoria=<?php echo $linha["categoria_id"] ?>">Alterar</a></li>
					    <li style="width: 50px"><a href="dados.php?acao=exclusao&categoria=<?php echo $linha["categoria_id"] ?>">Excluir</a></li>
					</ul>
					<?php } ?>	
				</div>

				<div style="width: 240px; margin-left: 12px; margin-top: 20px">
				<small style="font-size: 55%;"><sup>*</sup><u><b>Atenção</b></u>: a exclusão de categorias acarretará também na exclusão dos produtos relacionados</small>
				</div>
			</div>




			<div style="background-color: #F8F8F8; padding: 5px 5px 15px 5px; width: 375px; position: relative; left: 280px;">
			<h2 style="margin-left: 12px;">Produtos</h2>

			<?php 
			
				$consulta = "SELECT * FROM produtos";
				$acesso = mysqli_query($conecta, $consulta); ?>

				<div style="width: 350px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 100px; margin-left: 10px">
					<form action="dados.php?acao=<?php echo $acao; ?>&produto=<?php echo $produto_id; ?>" method="post">
						
						<div style="float: left; margin-right: 10px">
							<label for="categoria_produto">Categoria: </label>
							<select id="categoria_produto" name="categoria_produto" style="width: 150px">
								<option></option>
								<?php 
							    $consulta2 = "SELECT * FROM categorias";
								$acesso2 = mysqli_query($conecta, $consulta2); 
							    while ($dados2 = mysqli_fetch_assoc($acesso2)) { ?>
							     	<option value="<?php echo $dados2["categoria_id"]; ?>" <?php if($produtos["categoria_id"] == $dados2["categoria_id"]) { ?> selected <?php } ?>><?php echo utf8_encode($dados2["categoria"]); ?></option>
							     <?php } ?>
							</select>
						</div>
						
						<div>
							<label for="produto">Novo produto: </label>
							<input type="text" id="produto" name="produto" value="<?php echo(utf8_encode($produtos["produto"])); ?>" style="width: 150px" required>
						</div>

						<div style="float: left; margin-right: 10px">
							<label for="url_imagem">URL imagem: </label>
							<input type="url" id="url_imagem" name="url_imagem" value="<?php echo(utf8_encode($produtos["url_imagem"])); ?>" style="width: 215px">
						</div>

						<div>
							<input type="submit" id="botao" value="<?php 
							if ($acao == "alteracao") echo "Alterar";
							if ($acao == "cadastro") echo "Cadastrar";
						?>" style="width: 70px; height: 20px; padding: 2px; margin-left: 10px; margin-top: 25px">
						</div>
					</form>
				</div>
				<br>

				<div id="cima_tabela" style="width: 350px; margin-left: 10px">
					<ul>
					    <li style="width: 95px;"><b>Categoria</b></li>
					    <li style="width: 115px;"><b>Produto</b></li>
					</ul>
				</div>

				<div id="janela" style="width: 350px; margin-left: 10px">
					<?php
					    while($linha = mysqli_fetch_assoc($acesso)) {
					?>
					<ul>
					    <li style="width: 95px"><?php 
					    $consulta2 = "SELECT * FROM categorias WHERE categoria_id = {$linha["categoria_id"]}";
						$acesso2 = mysqli_query($conecta, $consulta2); 
						$dados2 = mysqli_fetch_assoc($acesso2);
					    echo utf8_encode($dados2["categoria"]); ?></li>
					    <li style="width: 115px"><?php echo utf8_encode($linha["produto"]) ?></li>
					    <li style="width: 50px"><a href="dados.php?acao=alteracao&produto=<?php echo $linha["produto_id"] ?>">Alterar</a></li>
					    <li style="width: 50px"><a href="dados.php?acao=exclusao&produto=<?php echo $linha["produto_id"] ?>">Excluir</a></li>
					</ul>
					<?php } ?>	
				</div>
			</div>
			</div><br><br>

		</article>	
		

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>	

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>