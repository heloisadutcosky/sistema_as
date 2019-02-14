<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";
	$n_opcoes = isset($_POST["n_opcoes"]) ? $_POST["n_opcoes"] : 0;

	if(isset($_GET["codigo"])) {
		$_SESSION["categoria_id"] = $_GET["codigo"];
	}
	if(isset($_GET["categoria"])) {
		$_SESSION["categoria"] = $_GET["categoria"];
	}

	if (isset($_POST["opcao" . ($n_opcoes - 1)])) {
		$classe = utf8_decode($_POST["classe"]);
		$opcao = utf8_decode($_POST["opcao" . ($n_opcoes - 1)]);
		
		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------
			$consulta_marca = "SELECT * FROM consumo_opcoes WHERE categoria_id = {$_SESSION["categoria_id"]} AND classe = '{$classe}' AND opcao = '{$opcao}'";

			$acesso = mysqli_query($conecta, $consulta_marca);
			$existe_opcao = mysqli_fetch_assoc($acesso);

			if (empty($existe_opcao)) {


				$cadastrar = "INSERT INTO consumo_opcoes (categoria_id, classe, opcao) VALUES ({$_SESSION["categoria_id"]}, '{$classe}', '{$opcao}')";
				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro");
				}
			}
		}
	}

	if (isset($_GET["opcao"])) {

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM consumo_opcoes WHERE opcao_id = {$_GET["opcao"]}";

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
	<title>Categorias - Classes</title>
	
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

	div#janela li:nth-child(3) a {
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
			<h2 class="espaco">Classes - <?php echo $_SESSION["categoria"]; ?></h2>
			<br>

			<form action="classes.php?acao=cadastro" method="post">
				<div style="width: 270px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 50px; margin-left: 20px">
						<div style="float: left; margin-right: 10px;">
							<label for="classe">Classe: </label>
							<input type="text" id="classe" name="classe" 
							<?php
							if(isset($_POST["classe"])) { ?>
								value="<?php echo($_POST["classe"]); ?>"
							<?php } ?>
							required>
						</div>
				</div><br>

				<div style="width: 270px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; margin-left: 20px">
					<div style="margin-right: 10px;">
						<label for="opcao">Opções: </label>
						<?php 
						foreach (range(0, $n_opcoes) as $i) { ?>
							<?php
								if (!empty($existe_opcao) && $i == $n_opcoes - 1) { ?>
								<p style="margin-left: 15px; font-size: 80%; color: red;">Essa opção já foi cadastrada</p>
							<?php } else { ?>
								<input type="opcao" id="opcao" name="opcao<?php echo($i); ?>"
								<?php 
								if(isset($_POST["opcao{$i}"])) { ?>
									value="<?php echo($_POST["opcao{$i}"]); ?>"
								<?php } ?>
								required>
							<?php } ?>
						<?php } ?>
					</div>
					<input type="hidden" name="n_opcoes" value="<?php echo ($n_opcoes + 1); ?>">
					<button type="submit" value="+" style="margin-right: 20px; float: right; margin-top: -30px;"><b style="font-size: 125%;">+</b></button><br>
				</div><br>

					<div style="margin-left: 15px;">
						<input type="submit" id="botao" value="Cadastrar" style="width: 100px; height: 20px; padding-top: 2px">
					</div><br><br>
				</form>

			

			<?php 
			if (isset($_SESSION["categoria_id"])) {

				$consulta = "SELECT * FROM consumo_opcoes WHERE categoria_id = {$_SESSION["categoria_id"]}";
				$acesso = mysqli_query($conecta, $consulta); 
			?>
				<div id="janela" style="width: 400px">
					<?php while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<ul>
					    <li style="width: 100px"><?php echo utf8_encode($linha["classe"]) ?></li>
					    <li style="width: 220px"><?php echo utf8_encode($linha["opcao"]) ?></li>
					    <li style="width: 50px"><a href="classes.php?acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>&opcao=<?php echo utf8_encode($linha["opcao_id"]) ?>">Excluir</a> </li>
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