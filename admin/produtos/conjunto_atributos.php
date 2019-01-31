<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";
	$categoria_id = isset($_GET["codigo"]) ? $_GET["codigo"] : 0;
	$categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : 0;

	if (isset($_POST["conjunto_atributos"])) {
		$conjunto_atributos = utf8_decode($_POST["conjunto_atributos"]);
		$descricao_conjunto = utf8_decode($_POST["descricao_conjunto"]);

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência da categoria na base ------------------------------
			$consulta_conjunto = "SELECT * FROM conjuntos_atributos WHERE categoria_id = {$categoria_id} AND conjunto_atributos = '{$conjunto_atributos}'";

			$acesso = mysqli_query($conecta, $consulta_conjunto);
			$existe_conjunto = mysqli_fetch_assoc($acesso);

			if (!empty($existe_conjunto)) { ?>
				<p>Esse conjunto de atributos já foi cadastrado</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO conjuntos_atributos (categoria_id, conjunto_atributos, descricao_conjunto) VALUES ({$categoria_id}, '{$conjunto_atributos}', '{$descricao_conjunto}')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro da marca.");
				} else {
					header("location:conjuntos.php");
				}
			}
		}
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
		<h2 class="espaco">Conjunto de atributos</h2>
		<br>

		<?php 
					$consulta = "SELECT * FROM conjunto_atributos WHERE categoria_id = {$categoria_id}";
					$acesso = mysqli_query($conecta, $consulta); ?>

					<form action="dados.php?tipo=categorias&acao=cadastro" method="post">
						<label for="conjunto_atributos">Novo conjunto de atributos: </label>
						<input type="text" id="conjunto_atributos" name="conjunto_atributos" required>

						<label for="descricao_conjunto">Novo conjunto de atributos: </label>
						<input type="text" id="descricao_conjunto" name="descricao_conjunto" required>

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
						    <li style="width: 50px"><a href="dados.php?tipo=categorias&acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>">
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