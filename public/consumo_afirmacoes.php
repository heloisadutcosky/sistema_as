<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

		$data_registro = date("Y-m-d");

		$consulta = "SELECT * FROM form_consumo WHERE categoria_id = {$_SESSION["categoria_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
	
		while ($linha = mysqli_fetch_assoc($acesso)) {
			if(isset($_POST["caract{$linha["caracteristica_id"]}"])) {
				$_SESSION["caract{$linha["caracteristica_id"]}"] = $_POST["caract{$linha["caracteristica_id"]}"];
	
				if ($_SESSION["teste"] == 0) {

					$consulta3 = "SELECT * FROM result_consumo WHERE caracteristica_id = {$linha["caracteristica_id"]} AND user_id = {$_SESSION["user_id"]}";
					$acesso3 = mysqli_query($conecta, $consulta3);
					$preenchida = mysqli_fetch_assoc($acesso3);

					if (empty($preenchida)) {
						$inserir = "INSERT INTO result_consumo (caracteristica_id, user_id, categoria_id, resposta, data_registro) VALUES ('{$linha["caracteristica_id"]}', '{$_SESSION["user_id"]}', '{$_SESSION["categoria_id"]}', '{$resposta}', '{$data_registro}')";
						$operacao_inserir = mysqli_query($conecta, $inserir);	
					} else {
						$alterar = "UPDATE result_consumo SET caracteristica_id = '{$linha["caracteristica_id"]}', user_id = '{$_SESSION["user_id"]}', categoria_id = '{$_SESSION["categoria_id"]}', resposta = '{$resposta}', data_registro = '{$data_registro}' WHERE caracteristica_id = {$linha["caracteristica_id"]} AND user_id = {$_SESSION["user_id"]}";

						$operacao_alterar = mysqli_query($conecta, $alterar);
				}
			}
		}
	}
	
	if (isset($_POST["botao"])) {
		header("location:{$_SESSION["tipo_avaliador"]}/{$_SESSION["tipo_avaliacao"]}/principal.php");
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Questionário de consumo</title>
	
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

		<h2 class="espaco">QUESTIONÁRIO DE HÁBITOS DE CONSUMO (2/2)</h2>
		<br>

		<article style="margin-left: 10px">
		<p>Por favor, selecione o quanto você concorda ou discorda com as seguintes afirmações:</p><br>

		<form action="consumo_afirmacoes.php" method="post">

			<?php 
					
			$consulta = "SELECT * FROM form_consumo WHERE categoria_id = {$_SESSION["categoria_id"]}";
			$acesso = mysqli_query($conecta, $consulta);

			while ($form_consumo = mysqli_fetch_assoc($acesso)) { 

				if ($form_consumo["disposicao_pergunta"] == "lista" && $form_consumo["classe"] == "Afirmativa") { ?>

					<div>
					<p><?php echo utf8_encode($form_consumo["pergunta"]); ?></p>
					<div style="margin-left: 5px;">
						<?php 
						$consulta_opcoes = "SELECT * FROM consumo_opcoes WHERE categoria_id = 0";
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
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
if (isset($acesso3)) {
	mysqli_free_result($acesso3);
}
if (isset($acesso_opcoes)) {
	mysqli_free_result($acesso_opcoes);
}
if (isset($operacao_inserir)) {
	mysqli_free_result($operacao_inserir);
}
if (isset($operacao_alterar)) {
	mysqli_free_result($operacao_alterar);
}
	mysqli_close($conecta);
?>