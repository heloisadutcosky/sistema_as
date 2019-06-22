<?php

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "cadastro";

	// Abrir consulta ao banco de dados para pegar informações do usuário selecionado
	if (isset($_GET["codigo"])) {
		$formulario_id = $_GET["codigo"];
		$_SESSION["formulario_id"] = $formulario_id;
	} else {
		$formulario_id = 0;
	}

	$consulta = "SELECT * FROM tb_formularios WHERE formulario_id = {$formulario_id}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["categoria_id"])) {
		$nome_formulario = utf8_decode($_POST["nome_formulario"]);
		$tipo_formulario = utf8_decode($_POST["tipo_formulario"]);
		$categoria_id = $_POST["categoria_id"];
		$produto_id = empty($_POST["produto_id"]) ? 0 : $_POST["produto_id"];
		$projeto_id = 0;
		$escala_min = empty($_POST["escala_min"]) ? 0 : $_POST["escala_min"];
		$escala_max = empty($_POST["escala_max"]) ? 0 : $_POST["escala_max"];
		$form_ativo = isset($_POST["form_ativo"]) ? 1 : 0;
		
			if ($_POST["atualizar"]=="Yes") {

				echo $_POST["atualizar"];
				
				// Alterar cadastro ---------------------------------------------------------
				if ($acao == "alteracao") {
						
					$alterar = "UPDATE tb_formularios SET nome_formulario = '{$nome_formulario}', tipo_formulario = '{$tipo_formulario}', categoria_id = {$categoria_id}, produto_id = {$produto_id}, projeto_id = {$projeto_id}, escala_min = '{$escala_min}', escala_max = '{$escala_max}', form_ativo = '{$form_ativo}' WHERE formulario_id = {$formulario_id}";

					echo $alterar;

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

					// Verificar existência do projeto na base ------------------------------

					//$consulta_projeto = "SELECT * FROM tb_formularios WHERE categoria_id = {$categoria_id} AND produto_id = {$produto_id} AND projeto_id = {$projeto_id} AND tipo_formulario = '{$tipo_formulario}'";

					//$acesso = mysqli_query($conecta, $consulta_projeto);
					//$existe_projeto = mysqli_fetch_assoc($acesso);

					
					// ----------------------------------------------------------------------
						
					//else {
						$cadastrar = "INSERT INTO tb_formularios (nome_formulario, tipo_formulario, categoria_id, produto_id, projeto_id, escala_min, escala_max, form_ativo) VALUES ('{$nome_formulario}', '{$tipo_formulario}', {$categoria_id}, {$produto_id}, {$projeto_id}, '{$escala_min}', '{$escala_max}', '{$form_ativo}')";

						$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

						if (!$operacao_cadastrar) {
							echo $cadastrar;
							die("Falha no cadastro dos dados.");
						} else {
							header("location:dados.php");
						}
					//}
				}
				// --------------------------------------------------------------------------

				// Excluir cadastro ---------------------------------------------------------
				if ($acao == "exclusao") {
						
					$excluir = "DELETE FROM tb_formularios WHERE formulario_id = {$formulario_id}";

					$operacao_excluir = mysqli_query($conecta, $excluir);

					if (!$operacao_excluir) {
						die("Falha na exclusão dos dados.");
					} else {
						header("location:dados.php");
					}
				}
				// --------------------------------------------------------------------------
			}
	} else {
		$nome_formulario = $dados["nome_formulario"];
		$tipo_formulario = $dados["tipo_formulario"];
		$categoria_id = $dados["categoria_id"];
		$produto_id = $dados["produto_id"];
		$escala_min = $dados["escala_min"];
		$escala_max = $dados["escala_max"];
		$form_ativo = $dados["form_ativo"];
	}
	// ------------------------------------------------------------------------------

	// Liberar dados da memória
	mysqli_free_result($acesso);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Alteração de form</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "ALTERAÇÃO DE FORMULÁRIO";
			elseif ($acao == "exclusao") echo "EXCLUSÃO DE FORMULÁRIO";
			else echo "CADASTRO DE FORMULÁRIO"; 
			?></h2>

		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $formulario_id; ?>" method="post" id="myForm">
			<div style="background-color: #F8F8F8; padding: 15px 5px 15px 5px; width: 600px">
				<div>
					<label for="nome_formulario">Nome para o formulário<sup>*</sup>: </label>
					<input type="text" id="nome_formulario" name="nome_formulario" value="<?php echo utf8_encode($nome_formulario); ?>" style="margin-bottom: 1px; width: 555px">
					<small style="font-size: 55%; margin-left: 10px;"><sup>*</sup>Nome para identificação e que aparecerá para os usuários</small>
				</div><br><br>

				<div style="float: left; margin-right: 30px;">
					<label for="tipo_formulario">Tipo de formulário: </label>
					<select type="text" id="tipo_formulario" name="tipo_formulario" style="width: 315px">
						<option></option>
						<option value="livre" <?php if($tipo_formulario == "livre") { ?> selected <?php } ?>>Livre</option>
						<option value="pdq" <?php if($tipo_formulario == "pdq") { ?> selected <?php } ?>>Painel descritivo quantitativo</option>
					</select>
				</div>

				<div style="float: left; margin-right: 10px;">
					<label for="escala_min">Escala mínima: </label>
					<input type="number" id="escala_min" name="escala_min" value="<?php echo $escala_min; ?>" style="width: 80px;">
				</div>

				<div>
					<label for="escala_max">Escala máxima: </label>
					<input type="number" id="escala_max" name="escala_max" value="<?php echo $escala_max; ?>" style="width: 80px;">
				</div><br><br>

				<div style="float: left; margin-right: 30px;">
					<label for="categoria_id">Categoria: </label>
					<select id="categoria_id" name="categoria_id" style="width: 260px" onchange="pegaCategoriaId()"><br>
						<option></option>
						<?php 
						$consulta2 = "SELECT * FROM categorias";
						$acesso2 = mysqli_query($conecta, $consulta2);
						while($linha = mysqli_fetch_assoc($acesso2)) { ?>
							<option value="<?php echo $linha["categoria_id"]; ?>" <?php if($categoria_id == $linha["categoria_id"]) { ?> selected <?php } ?>><?php echo utf8_encode($linha["categoria"]); ?></option>
						<?php } ?>
					</select>
				</div>

				<script type="text/javascript">
		          function pegaCategoriaId() {
		                var categoriaId = document.getElementById("categoria_id").value;
		                document.getElementById("atualizar").value = "No";
		                document.getElementById("myForm").submit();
		            }
		        </script>

		        <input type="hidden" id="atualizar" name="atualizar" value="Yes">

				<div>
					<label for="produto_id">Produto: </label>
					<?php 
						$consulta3 = "SELECT * FROM produtos WHERE categoria_id = {$categoria_id}";
						$acesso3 = mysqli_query($conecta, $consulta3);
						?>
					<select id="produto_id" name="produto_id" style="width: 260px"><br>
						<option></option>
						<?php 
						while($linha = mysqli_fetch_assoc($acesso3)) { ?>
							<option value="<?php echo $linha["produto_id"]; ?>" <?php if($produto_id == $linha["produto_id"]) { ?> selected <?php } ?>><?php echo utf8_encode($linha["produto"]); ?></option>
						<?php } ?>
					</select>
				</div>

			</div><br><br>

			<div>
				<input type="submit" id="botao" value="<?php 
					if ($acao == "alteracao") echo "Alterar cadastro";
					if ($acao == "exclusao") echo "Excluir cadastro";
					if ($acao == "cadastro") echo "Cadastrar";
				?>"><br>
				<br>
			</div>

		</form>
		</article>

		<div class="direita">
			<a href="dados.php">Voltar</a><br><br>
		</div>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>