<?php 

	$caminho =  "../../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	// Abrir consulta ao banco de dados
	$consulta = "SELECT * FROM formularios WHERE projeto_id = {$_SESSION["projeto_id"]}";
	$acesso = mysqli_query($conecta, $consulta);

	// Armazenar respostas anteriores
	if ($_SESSION["teste"] == 0) {
		foreach ($_SESSION["amostras"] as $amostra) {
			if (isset($_POST["$amostra"])) {

				$conjunto_atributos = utf8_decode($dados["conjunto_atributos"]);
				$atributo = $dados["atributo_completo"];
				$nota = $_POST["$amostra"]*10;

				$inserir = "INSERT INTO resultados (projeto_id, sessao, user_id, amostra_codigo, atributo_completo, nota) VALUES ($projeto_id, $sessao, $user_id, '$amostra', '$atributo', $nota)";

				if (!$acesso) {
					die("Falha na insercao dos dados.");
				}

				$operacao_inserir = mysqli_query($conecta, $inserir);
			}
		}
	}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Escala hedônica</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo $caminho; ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

	<style>
		.folha_cadastro {
		  margin-bottom: 1px;
		  padding: 5px 15px;
		  z-index: -1;
		}

		ul.opcoes {
		  margin-left: -30px;
		}

		button {	
		  display: inline-block;
		  text-decoration: none;
		  background-color: #F7F6F6;
		  margin: 1px;
		  padding: 5px 5px;
		  color: #626161;

		  border: 1px solid #C1B7B7;
		  width: 100px;
		  height: 60px;
		  vertical-align: middle;
		  text-decoration: none;
		  text-align: center;

		  font-size: 75%;
		  color: #626161;
		}

		button:hover {
		  background-color: #FFE1E1;
		}

	</style>

	<script type="text/javascript">
		function sendForm() {
			document.myForm.submit();
		}
	</script>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<h2 class="espaco"><?php echo $_SESSION["produto"]; ?></h2>
		<br>

		<article style="margin-left: 10px">
		<p>Por favor, responda as seguintes perguntas sobre os seus hábitos de consumo de 
		<b style="font-size: 120%; color: #C2534B;"><?php echo utf8_encode($_SESSION["categoria"]); ?></b>
		: 
		</p><br>

		<form action="form_consumo.php" method="post">

			<?php 
					
			$consulta = "SELECT * FROM form_consumo WHERE categoria_id = {$_SESSION["categoria_id"]}";
			$acesso = mysqli_query($conecta, $consulta);

			while ($form_consumo = mysqli_fetch_assoc($acesso)) { 

				if ($form_consumo["disposicao_pergunta"] == "lista" && $form_consumo["classe"] != "Afirmativa") { ?>

					<div>
					<p><?php echo utf8_encode($form_consumo["pergunta"]); ?></p>
					<div style="margin-left: 5px;">
						<?php 
						$consulta_opcoes = "SELECT * FROM consumo_opcoes WHERE categoria_id = {$_SESSION["categoria_id"]} AND classe = '{$form_consumo["classe"]}'";
						$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes);

						while ($consumo_opcoes = mysqli_fetch_assoc($acesso_opcoes)) {
						?>
							<button type="submit" name="caract<?php echo $form_consumo["caracteristica_id"]; ?>" value="<?php echo $consumo_opcoes["opcao_id"]; ?>" <?php if (isset($_SESSION["caract{$form_consumo["caracteristica_id"]}"])) {
								if ($_SESSION["caract{$form_consumo["caracteristica_id"]}"] == $consumo_opcoes["opcao_id"]) { ?>
							 		style="background-color: #FFE1E1;"
							 	<?php } } ?>><?php echo utf8_encode($consumo_opcoes["opcao"]); ?></button>
						<?php } ?>
					</div>
					</div><br><br>

				<?php } ?>
				
				<?php 
				if ($form_consumo["disposicao_pergunta"] == "checkbox") { ?>
					<div>
						<p><?php echo utf8_encode($form_consumo["pergunta"]); ?></p>
						<div style="margin-left: -10px;">
							<?php 
								$consulta_opcoes = "SELECT * FROM consumo_opcoes WHERE categoria_id = {$_SESSION["categoria_id"]} AND classe = '{$form_consumo["classe"]}'";
								$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes);
								while ($consumo_opcoes = mysqli_fetch_assoc($acesso_opcoes)) { ?>
									<div style="float: left;">
										<label for="<?php echo $consumo_opcoes["opcao_id"]; ?>" style="margin-right: 20px; float: left;">
											<input type="checkbox" name="caract<?php echo $form_consumo["caracteristica_id"]; ?>[]" id="<?php echo $consumo_opcoes["opcao_id"]; ?>" value="<?php echo $consumo_opcoes["opcao"]; ?>" style="width: 10px; float: left;"
												<?php 
												if (isset($_POST["caract{$form_consumo["caracteristica_id"]}"])) {
													if (in_array($consumo_opcoes["opcao"], $_POST["caract{$form_consumo["caracteristica_id"]}"])) { ?>
												checked
													<?php }
												} ?>
											 />
											<?php echo utf8_encode($consumo_opcoes["opcao"]); ?>
										</label><br>
									</div>
								<?php 
									if(strpos("x".strtolower($consumo_opcoes["opcao"]), "outr")){
										$outro = strtolower($consumo_opcoes["opcao"]);};
									} ?>
						</div>
						<?php if (isset($outro)) { ?>
							<br><br>
							<div>
								<label for="outro">Se <?php echo $outro; ?>s, favor indicar quais: </label>
								<input type="text" name="caract<?php echo $form_consumo["caracteristica_id"]; ?>outro" id="outro" 
								<?php 
								if (isset($_POST["caract{$form_consumo["caracteristica_id"]}outro"])) { ?>
									value="<?php echo $_POST["caract{$form_consumo["caracteristica_id"]}outro"]; ?>">
								<?php } ?>
							</div>
						<?php } ?>	
						</div><br>
				<?php } ?>

				<?php 
				if ($form_consumo["disposicao_pergunta"] == "select") { ?>
					<div>
						<p><?php echo utf8_encode($form_consumo["pergunta"]); ?></p>
						<div>
							<select name="caract<?php echo $form_consumo["caracteristica_id"]; ?>">
								<option value="NA"></option>
								<?php 
								$consulta_opcoes = "SELECT * FROM consumo_opcoes WHERE categoria_id = {$_SESSION["categoria_id"]} AND classe = '{$form_consumo["classe"]}'";
								$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes);
								while ($consumo_opcoes = mysqli_fetch_assoc($acesso_opcoes)) { ?>
									<option value="<?php echo $consumo_opcoes["opcao"]; ?>" 
										<?php 
										if (isset($_POST["caract{$form_consumo["caracteristica_id"]}"])) {
											if ($consumo_opcoes["opcao"] == $_POST["caract{$form_consumo["caracteristica_id"]}"]) { ?>
										selected
											<?php }
										} ?>
										><?php echo utf8_encode($consumo_opcoes["opcao"]); ?></option>
								<?php 
									if(strpos("x".strtolower($consumo_opcoes["opcao"]), "outr")){$outro = strtolower($consumo_opcoes["opcao"]);};
								} ?>
							</select>
						</div>
						<?php if (isset($outro)) { ?>
							<div>
								<label for="outro">Se <?php echo $outro; ?>, favor indicar qual: </label>
								<input type="text" name="caract<?php echo $form_consumo["caracteristica_id"]; ?>outro" id="outro"
								<?php 
								if (isset($_POST["caract{$form_consumo["caracteristica_id"]}outro"])) { ?>
									value="<?php echo $_POST["caract{$form_consumo["caracteristica_id"]}outro"]; ?>">
								<?php } ?>
							</div>
						<?php } ?>
					</div><br>
				<?php } ?>
			<?php } ?>

			<input type="submit" id="botao" name="botao" value="Enviar"><br>
		</form>
		<br>
		</article>


		<br>
		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
