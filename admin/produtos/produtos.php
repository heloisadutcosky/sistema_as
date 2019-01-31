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
	$categoria_id = isset($_GET["codigo"]) ? $_GET["codigo"] : "";

	if (isset($_GET["produto_id"])) {
		$produto_id = $_GET["produto_id"];

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM produtos WHERE produto_id = {$produto_id}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				//header("location:produtos.php?codigo=" . $categoria_id . "&categoria=" . $_SESSION["categoria"]);
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
		<h2 class="espaco">Produtos - <?php echo $_SESSION["categoria"]; ?></h2>
		<br>

		<?php 
			if (isset($_GET["codigo"])) {
				$categoria_id = $_GET["codigo"];

				$consulta = "SELECT * FROM produtos WHERE categoria_id = " . $categoria_id;
				$acesso = mysqli_query($conecta, $consulta); ?>

				<div class="botao">
					<a class="espaco" href="cadastro_produto.php">Cadastrar produto</a><br>
				</div>
				<br>

				<div id="cima_tabela" style="width: 430px">
					<ul>
					    <li style="width: 100px"><b>Produto</b></li>
					    <li style="width: 100px"><b>Categoria</b></li>
					    <li style="width: 70px"><b>Sabor</b></li>
					    <li style="width: 70px"><b>Marca</b></li>
					</ul>
				</div>

				<div id="janela" style="width: 430px">
					<?php while($linha = mysqli_fetch_assoc($acesso)) { 
						$consulta2 = "SELECT * FROM categorias WHERE categoria_id = '{$linha["categoria_id"]}'";
						$acesso2 = mysqli_query($conecta, $consulta2);
						$dados = mysqli_fetch_assoc($acesso2);?>
					<ul>
					    <li style="width: 100px"><?php echo utf8_encode($linha["produto"]); ?></li>
						<li style="width: 100px"><?php echo utf8_encode($dados["categoria"]); ?></li>
						<li style="width: 70px"><?php echo utf8_encode($linha["sabor"]); ?></li>
						<li style="width: 70px"><?php echo utf8_encode($linha["marca"]); ?></li>
						<li style="width: 50px"><a href="produtos.php?acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>&categoria=<?php echo utf8_encode($dados["categoria"]); ?>&produto_id=<?php echo $linha["produto_id"] ?>">Excluir</a>
					</ul>
					<?php } ?>	
				</div>
			<?php } ?>

		<div class="direita">
			<a href="dados.php?tipo=categorias">Voltar</a><br><br>
		</div>
		
		<br>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>