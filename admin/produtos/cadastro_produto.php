<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";

	if (isset($_POST["produto"])) {
		$categoria_id = $_POST["categoria_id"];
		$produto = utf8_decode($_POST["produto"]);
		$sabor = utf8_decode($_POST["sabor"]);
		$marca = utf8_decode($_POST["marca"]);

	// Cadastrar ----------------------------------------------------------------

		// Verificar existência do projeto na base ------------------------------

		$consulta_produto = "SELECT * FROM produtos WHERE categoria_id = {$categoria_id} AND marca = '{$marca}' AND sabor = '{$sabor}'";

		$acesso = mysqli_query($conecta, $consulta_produto);
		$existe_produto = mysqli_fetch_assoc($acesso);

		if (!empty($existe_produto)) { ?>
			<p>Esse produto já foi cadastrado</p>
		<?php } 

		// ----------------------------------------------------------------------
			
		else {
			$cadastrar = "INSERT INTO produtos (categoria_id, produto, sabor, marca) VALUES ({$categoria_id}, '{$produto}', '{$sabor}', '{$marca}')";

			$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

			if (!$operacao_cadastrar) {
				die("Falha no cadastro da marca.");
			} else {
				header("location:dados.php?tipo=produtos");
			}
		}
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

	<script type="text/javascript">var cat = document.getElementById('categoria_id').value;</script>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco">Cadastro produto</h2>
		<br>

		<?php if(isset($_GET["categoria_id"])) { ?>
		<form action="cadastro_produto.php" method="post">
			<label for="produto">Nome produto: </label>
			<input type="text" id="produto" name="produto" value="<?php echo $_GET["produto"]; ?>" required>

			<label for="categoria_id">Tabela: </label>
			<select id="categoria_id" name="categoria_id"><br>
				<?php 
				$consulta = "SELECT * FROM categorias";
				$acesso = mysqli_query($conecta, $consulta);
				while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<?php if($linha["categoria_id"] == $_GET["categoria_id"]) { ?>
						<option value="<?php echo $linha["categoria_id"]; ?>" selected><?php echo utf8_encode($linha["categoria"]); ?></option>
					<?php } else { ?>
						<option value="<?php echo $linha["categoria_id"]; ?>"><?php echo utf8_encode($linha["categoria"]); ?></option>
					<?php } ?>
				<?php } ?>
			</select>

			<label for="sabor">Sabor: </label>
			<select id="sabor" name="sabor"><br>
				<?php
				$consulta = "SELECT * FROM sabores where categoria_id = " . $_GET["categoria_id"];
				$acesso = mysqli_query($conecta, $consulta);
				while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<option value="<?php echo $linha["sabor"]; ?>"><?php echo utf8_encode($linha["sabor"]); ?></option>
				<?php } ?>
			</select>

			<label for="marca">Marca: </label>
			<select id="marca" name="marca"><br>
				<?php
				$consulta = "SELECT * FROM marcas where categoria_id = " . $_GET["categoria_id"];
				$acesso = mysqli_query($conecta, $consulta);
				while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<option value="<?php echo $linha["marca"]; ?>"><?php echo utf8_encode($linha["marca"]); ?></option>
				<?php } ?>
			</select>
			
			<input type="submit" id="botao" value="Cadastrar">
		</form>
		<?php } else { ?>

		<form action="cadastro_produto.php" method="get">
			<label for="produto">Nome produto: </label>
			<input type="text" id="produto" name="produto" required>

			<label for="categoria_id">Tabela: </label>
			<select id="categoria_id" name="categoria_id"><br>
				<?php 
				$consulta = "SELECT * FROM categorias";
				$acesso = mysqli_query($conecta, $consulta);
				while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<option value="<?php echo $linha["categoria_id"]; ?>"><?php echo utf8_encode($linha["categoria"]); ?></option>
				<?php } ?>
			</select>

			<input type="submit" id="botao" value="continuar">
		</form>
		<br>
		<?php } ?>

		<div class="direita">
			<a href="dados.php?tipo=produtos">Voltar</a><br><br>
		</div>
		
		<br>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>