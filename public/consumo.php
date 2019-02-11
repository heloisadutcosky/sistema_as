<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	// Verificar se usuário já preencheu o questionário pra categoria em questão
	if (isset($_SESSION["categoria_id"])) {
		$consulta = "SELECT * FROM consumo WHERE user_id = {$_SESSION["user_id"]} AND categoria_id = {$_SESSION["categoria_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);

		if (!empty($dados)) {
			header("location:" . strtolower($_SESSION["funcao"]) . "/principal.php");
		} else {
			$consulta = "SELECT * FROM categorias WHERE categoria_id = {$_SESSION["categoria_id"]}"; 
			$acesso2 = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso2);
			$categoria = $dados["categoria"];
		}
	} else {
		header("location:principal.php");
	}

	if (isset($_POST["frequencia_consumo"])) {
		$fumante = isset($_POST["fumante"]) ? 1 : 0;

		$inserir = "INSERT INTO consumo (user_id, categoria_id, painelista, frequencia_consumo, sabores_consumidos, sabor_preferido, marcas_consumidas, marca_preferida, data_registro) VALUES ('{$_SESSION["user_id"]}', '{$_POST["categoria_id"]}', '{$_POST["painelista"]}', '{$_POST["frequencia_consumo"]}', '{$_POST["sabores_consumidos"]}', '{$_POST["sabor_preferido"]}', '{$_POST["marcas_consumidas"]}', '{$_POST["marca_preferida"]}', '{$_POST["data_registro"]}')";

		$operacao_inserir = mysqli_query($conecta, $inserir);

		header("location:painelista/principal.php?codigo={$_SESSION["projeto_id"]}&produto={$_SESSION["produto"]}");
		
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

	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<h2 class="espaco">QUESTIONÁRIO DE HÁBITOS DE CONSUMO</h2>
		<br>

		<article style="margin-left: 10px">
		<p style="width: 560px;">Por favor, responda as seguintes perguntas sobre os seus hábitos de consumo de 
		<b style="font-size: 120%; color: #C2534B;"><?php echo utf8_encode($categoria); ?></b>
		: 
		</p><br>

		
		<form action="consumo.php" method="post">

			<!-- CADASTRO -->
			<div>
				<p>Qual é sua frequência de uso de <?php echo strtolower(utf8_encode($categoria)); ?>? </p>
				<select name="frequencia_consumo">
					<option value="Mais de duas vezes por semana" required>Mais de duas vezes por semana</option>
				</select>
			</div><br>

			<div>
				<p>Quais sabores de <?php echo strtolower(utf8_encode($categoria)); ?> você consome? </p>
						<?php 
							$consulta = "SELECT * FROM sabores WHERE categoria_id = {$_SESSION["categoria_id"]}";
							$acesso = mysqli_query($conecta, $consulta);
							while ($dados = mysqli_fetch_assoc($acesso)) { ?>
								<div style="float: left">
									<input type="checkbox" name="sabores_consumidos[]" value="<?php echo $dados["sabor"]; ?>" style="width: 2px; float: left;" />
									<label for="sabores_consumidos[]" style="margin-right: 20px; float: left;"><?php echo utf8_encode($dados["sabor"]); ?></label><br>
								</div>
							<?php } ?>
							<div>
								<input type="checkbox" name="sabores_consumidos[]" style="width: 2px; float: left;" />
								<label for="sabores_consumidos[]" style="margin-right: 20px; float: left;">Outros</label><br>
							</div><br>
							<div>
								<label for="sabor_preferido">Se outros, favor indicar quais: </label>
								<input type="text" name="sabores_consumidos[]">
							</div>
			</div><br>

			<div>
				<p>Qual é seu sabor preferido de <?php echo strtolower(utf8_encode($categoria)); ?>? </p>
				<div>
					<select>
						<?php $consulta = "SELECT * FROM sabores WHERE categoria_id = {$_SESSION["categoria_id"]}";
						$acesso = mysqli_query($conecta, $consulta);
						while ($dados = mysqli_fetch_assoc($acesso)) { ?>
							<option name="sabor_preferido" value="<?php echo utf8_encode($dados["sabor"]); ?>"><?php echo utf8_encode($dados["sabor"]); ?></option>
						<?php } ?>
							<option name="sabor_preferido" value="Outro">Outro</option>
					</select>
				</div>
				<div>
					<label for="sabor_preferido">Se outro, favor indicar qual: </label>
					<input type="text" name="sabor_preferido">
				</div>
			</div><br>

			<div>
				<p>Quais marcas de <?php echo strtolower(utf8_encode($categoria)); ?> você consome? </p>
				<?php $consulta = "SELECT * FROM marcas WHERE categoria_id = {$_SESSION["categoria_id"]}";
				$acesso = mysqli_query($conecta, $consulta);
				while ($dados = mysqli_fetch_assoc($acesso)) { ?>
					<div style="float: left">
						<input type="checkbox" name="marcas_consumidas[]" style="width: 1px; float: left;">
						<label for="marcas_consumidas[]" style="margin-right: 20px; float: left;"><?php echo utf8_encode($dados["marca"]); ?></label><br>
					</div>
				<?php } ?>
			</div><br><br><br>

			<input type="submit" id="botao" value="Cadastrar dados"><br>
		</form>
		<br>
		</article>


		<br>
		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
