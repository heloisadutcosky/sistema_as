<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	if(isset($_GET["acao"])) {
		$acao = $_GET["acao"];
	} elseif (isset($_POST["acao"])) {
		$acao = $_POST["acao"];
	} else {
		$acao = "cadastro";
	}

	if(isset($_GET["codigo"])) {
		$_SESSION["categoria_id"] = $_GET["codigo"];
	}

	if(isset($_GET["categoria"])) {
		$_SESSION["categoria"] = $_GET["categoria"];
	}

	if (isset($_POST["pergunta"])) {
		$classe = utf8_decode($_POST["classe"]);
		$subclasse = utf8_decode($_POST["subclasse"]);
		$pergunta = utf8_decode($_POST["pergunta"]);
		$disposicao_pergunta = utf8_decode($_POST["disposicao_pergunta"]);
		
		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do projeto na base ------------------------------
			$consulta = "SELECT * FROM form_consumo WHERE categoria_id = {$_SESSION["categoria_id"]} AND classe = '{$classe}' AND subclasse = '{$subclasse}'";

			$acesso = mysqli_query($conecta, $consulta);
			$existe_subclasse = mysqli_fetch_assoc($acesso);

			if (empty($existe_subclasse)) {

				$cadastrar = "INSERT INTO form_consumo (categoria_id, classe, subclasse, pergunta, disposicao_pergunta) VALUES ({$_SESSION["categoria_id"]}, '{$classe}', '{$subclasse}', '{$pergunta}', '{$disposicao_pergunta}')";
				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
					die("Falha no cadastro");
				}
			} else {
				
			}
				// Alterar cadastro ---------------------------------------------------------
				if ($_POST["caracteristica"]) {

					$alterar = "UPDATE form_consumo SET classe = '{$classe}', subclasse = '{$subclasse}', pergunta = '{$pergunta}', disposicao_pergunta = '{$disposicao_pergunta}' WHERE caracteristica_id = {$_POST["caracteristica"]}";

					$operacao_alterar = mysqli_query($conecta, $alterar);
				}
				// --------------------------------------------------------------------------
		}
	}

	if (isset($_GET["caracteristica"])) {

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM form_consumo WHERE caracteristica_id = {$_GET["caracteristica"]}";

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
	<title>Formulário - Consumo</title>
	
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

	button {
	  text-decoration: none;
	  background-color: #778899;
	  margin-left: 10px;
	  margin-bottom: 1px;
	  padding: 5px 15px;
	  color: #FFF;
	  border: 1px solid #696969;
	  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
	}

	button:hover {
	  background-color: #DCDCDC;
	}

	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco">Questionário de hábitos de consumo - <?php echo $_SESSION["categoria"]; ?></h2>
			<br>
			<?php 
				if ($acao == "alteracao" && isset($_GET["caracteristica"])) {
					$consulta_alteracao = "SELECT * FROM form_consumo WHERE caracteristica_id = {$_GET["caracteristica"]}";
					$acesso_alteracao = mysqli_query($conecta, $consulta_alteracao);
					$alteracao = mysqli_fetch_assoc($acesso_alteracao);
				}
			?>

			<form action="form_consumo.php" method="post">
				<div style="width: 500px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; height: 50px; margin-left: 20px">
						<div style="float: left; margin-right: 10px;">
							<label for="classe">Classe: </label>
							<select id="classe" name="classe">
								<?php 
									$consulta = "SELECT * FROM consumo_opcoes WHERE categoria_id = '{$_SESSION["categoria_id"]}' OR categoria_id = 0";
									$acesso = mysqli_query($conecta, $consulta);
									
									$classes = array();
									while ($linha = mysqli_fetch_assoc($acesso)) { 
										$classes[] = $linha["classe"];
									} 
									$classes = array_values(array_unique($classes));
									?>

									<?php 
									foreach ($classes as $classe) { ?>
										<option value="<?php echo($classe); ?>" <?php 
										if ($acao == "alteracao" && isset($_GET["caracteristica"])) {
											if ($alteracao["classe"] == $classe) { ?>
												selected
											<?php } 
										} ?>>
										<?php echo(utf8_encode($classe)); ?></option>
									<?php 
									} ?>
							</select>
						</div>
						<div style="float: left; margin-right: 10px;">
							<label for="subclasse">Subclasse: </label>
							<input type="text" id="subclasse" name="subclasse" 
							<?php
							if ($acao == "alteracao" && isset($_GET["caracteristica"])) { ?>
								value="<?php echo($alteracao["subclasse"]); ?>"
							<?php } ?>
							required>
						</div>
				</div><br>

				<div style="width: 500px; background-color: #E3E3E3; padding-top: 10px; padding-left: 5px; margin-left: 20px">
					
							<div style="margin-right: 10px;">
							<label for="pergunta">Pergunta: </label>
							<?php
							if (!empty($existe_opcao) && $i == $n_opcoes - 1) { ?>
								<p style="margin-left: 15px; font-size: 80%; color: red;">Essa opção já foi cadastrada</p>
							<?php } 
							else { ?>
								<input type="pergunta" id="pergunta" name="pergunta" style="width: 440px;"
								<?php 
								if ($acao == "alteracao" && isset($_GET["caracteristica"])) { ?>
									value="<?php echo(utf8_encode($alteracao["pergunta"])); ?>"
								<?php } ?>
								required>
							<?php } ?>
							</div>

							<div style="margin-right: 10px;">
								<?php 
									$disp = isset($alteracao["disposicao_pergunta"]) ? $alteracao["disposicao_pergunta"] : ""; ?>
								<label for="disposicao_pergunta">Disposição da pergunta no form: </label>
									<select id="disposicao_pergunta" name="disposicao_pergunta">
										<option value="text" <?php if ($disp == "text") { ?>
											selected
										<?php } ?>>Texto livre</option>
										<option value="select" <?php if ($disp == "select") { ?>
											selected
										<?php } ?>>Seleção vertical</option>
										<option value="lista" <?php if ($disp == "lista") { ?>
											selected
										<?php } ?>>Seleção horizontal</option>
										<option value="checkbox" <?php if ($disp == "checkbox") { ?>
											selected
										<?php } ?>>Checkbox</option>
									</select>
							</div><br>
						<!--
					<input type="hidden" name="n_opcoes" value="<?php //echo ($n_opcoes + 1); ?>">
					<button type="submit" value="+" style="margin-right: 230px; float: right; margin-top: -48px;"><b style="font-size: 125%;">+</b></button><br>
					-->
				</div><br>

				<input type="hidden" name="caracteristica" value="<?php echo($_GET["caracteristica"]); ?>">


					<div style="margin-left: 15px;">
						<button type="submit" id="botao" value="cadastro" name="acao" style="width: 100px; height: 20px; padding-top: 2px; float: left; margin-right: 20px;">Cadastrar</button>
					</div><br><br>
				</form>

			

			<?php 
			if (isset($_SESSION["categoria_id"])) {

				$consulta = "SELECT * FROM form_consumo WHERE categoria_id = {$_SESSION["categoria_id"]} OR categoria_id = 0";
				$acesso = mysqli_query($conecta, $consulta); 
			?>
				<div id="janela" style="width: 500px">
					<?php while($linha = mysqli_fetch_assoc($acesso)) { ?>
					<ul>
					    <li style="width: 100px"><?php echo utf8_encode($linha["classe"]) ?></li>
					    <li style="width: 250px"><?php echo utf8_encode($linha["pergunta"]) ?></li>
					    <li style="width: 55px"><a href="form_consumo.php?acao=alteracao&codigo=<?php echo $linha["categoria_id"] ?>&caracteristica=<?php echo utf8_encode($linha["caracteristica_id"]) ?>">Alterar</a></li>
					    <li style="width: 50px"><a href="form_consumo.php?acao=exclusao&codigo=<?php echo $linha["categoria_id"] ?>&caracteristica=<?php echo utf8_encode($linha["caracteristica_id"]) ?>">Excluir</a> </li>
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

<?php 
	// Fechar conexão
	if (isset($acesso)) {
		mysqli_free_result($acesso);
	}
	mysqli_close($conecta);
?>