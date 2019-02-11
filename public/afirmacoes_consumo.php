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

		if (!empty($dados) && $_SESSION["funcao"] != "Administrador") {
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
		$painelista = $_SESSION["funcao"] == "Painelista" ? 1 : 0;
		$fumante = isset($_POST["fumante"]) ? 1 : 0;
		$data_registro = date("Y-m-d");

		// Computar sabores consumidos
		$consulta = "SELECT * FROM sabores WHERE categoria_id = {$_SESSION["categoria_id"]}";
		$acesso2 = mysqli_query($conecta, $consulta);
		
		$sabores = array();
		while ($dados = mysqli_fetch_assoc($acesso2)) {
			$sabores[] = $dados["sabor"];
		};
		
		$sabores_consumidos = "";
		foreach ($sabores as $sabor) { 
			$cons = in_array($sabor, $_POST["sabores_consumidos"]) ? 1 : 0;
			$sabores_consumidos = $sabores_consumidos . $cons;
		} 
		// ------------------------------

		// Computar sabor preferido
		$sabor_preferido = $_POST["sabor_preferido"] == "Outro" ? $_POST["sabor_preferido_outro"] : $_POST["sabor_preferido"];
		// ------------------------------


		// Computar marcas consumidas
		$consulta = "SELECT * FROM marcas WHERE categoria_id = {$_SESSION["categoria_id"]}";
		$acesso2 = mysqli_query($conecta, $consulta);
		
		$marcas = array();
		while ($dados = mysqli_fetch_assoc($acesso2)) {
			$marcas[] = $dados["marca"];
		};
		
		$marcas_consumidas = "";
		foreach ($marcas as $marca) { 
			$cons = in_array($marca, $_POST["marcas_consumidas"]) ? 1 : 0;
			$marcas_consumidas = $marcas_consumidas . $cons;
		} 
		// ------------------------------

		// Computar marca preferida
		$marca_preferida = $_POST["marca_preferida"] == "Outra" ? $_POST["marca_preferida_outra"] : $_POST["marca_preferida"];
		// ------------------------------

		$inserir = "INSERT INTO consumo (user_id, categoria_id, painelista, frequencia_consumo, sabores_consumidos, sabores_consumidos_outros, sabor_preferido, marcas_consumidas, marcas_consumidas_outras, marca_preferida, data_registro) VALUES ('{$_SESSION["user_id"]}', '{$_SESSION["categoria_id"]}', '{$painelista}', '{$_POST["frequencia_consumo"]}', '{$sabores_consumidos}', '{$_POST["sabores_consumidos_outros"]}', '{$_POST["sabor_preferido"]}', '{$marcas_consumidas}', '{$_POST["marcas_consumidas_outras"]}', '{$_POST["marca_preferida"]}', '{$data_registro}')";

		$operacao_inserir = mysqli_query($conecta, $inserir);

		//header("location:painelista/principal.php?codigo={$_SESSION["projeto_id"]}&produto={$_SESSION["produto"]}");
		
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
		<p>Por favor, responda as seguintes perguntas sobre os seus hábitos de consumo de 
		<b style="font-size: 120%; color: #C2534B;"><?php echo utf8_encode($categoria); ?></b>
		: 
		</p><br>

		
		<form action="consumo.php" method="post">

			<!-- CADASTRO -->
			<div>
				<p>Com qual frequência você consome <?php echo strtolower(utf8_encode($categoria)); ?>? </p>
				<select name="frequencia_consumo">
					<option value="Mais de duas vezes por semana" required>Mais de duas vezes por semana</option>
				</select>
			</div><br>

			<div>
				<?php 
					$consulta = "SELECT * FROM sabores WHERE categoria_id = {$_SESSION["categoria_id"]}";
					$acesso = mysqli_query($conecta, $consulta); 
					$rows = mysqli_num_rows($acesso); 
					if ($rows > 0) {
				?>
					<p>Quais sabores de <?php echo strtolower(utf8_encode($categoria)); ?> você costuma consumir? </p>
						<?php 
							$consulta = "SELECT * FROM sabores WHERE categoria_id = {$_SESSION["categoria_id"]}";
							$acesso = mysqli_query($conecta, $consulta);
							while ($dados = mysqli_fetch_assoc($acesso)) { ?>
								<div style="float: left;">
									<label for="<?php echo $dados["sabor"]; ?>" style="margin-right: 20px; float: left;">
										<input type="checkbox" name="sabores_consumidos[]" id="<?php echo $dados["sabor"]; ?>" value="<?php echo $dados["sabor"]; ?>" style="width: 5px; float: left;" />
										<?php echo utf8_encode($dados["sabor"]); ?>
									</label><br>
								</div>
							<?php } ?>
							<div>
								<label for="Outros" style="margin-right: 20px; float: left;">
									<input type="checkbox" name="sabores_consumidos[]" id="Outros" value="Outros" style="width: 5px; float: left;" />Outros
								</label><br>
							</div><br>
							<div>
								<label for="sabores_consumidos_outros">Se outros, favor indicar quais: </label>
								<input type="text" name="sabores_consumidos_outros">
							</div>
			</div><br>

			<div>
				<p>Qual é seu sabor preferido de <?php echo strtolower(utf8_encode($categoria)); ?>? </p>
				<div>
					<select name="sabor_preferido" id="sabor_preferido">
						<?php $consulta = "SELECT * FROM sabores WHERE categoria_id = {$_SESSION["categoria_id"]}";
						$acesso = mysqli_query($conecta, $consulta);
						while ($dados = mysqli_fetch_assoc($acesso)) { ?>
							<option value="<?php echo utf8_encode($dados["sabor"]); ?>"><?php echo utf8_encode($dados["sabor"]); ?></option>
						<?php } ?>
							<option value="Outro">Outro</option>
					</select>
				</div>
				<div>
					<label for="sabor_preferido">Se outro, favor indicar qual: </label>
					<input type="text" name="sabor_preferido">
				</div>
			</div><br>

				<?php } ?>

			<div>
				<p>Quais marcas de <?php echo strtolower(utf8_encode($categoria)); ?> você costuma consumir? </p>
						<?php 
							$consulta = "SELECT * FROM marcas WHERE categoria_id = {$_SESSION["categoria_id"]}";
							$acesso = mysqli_query($conecta, $consulta);
							while ($dados = mysqli_fetch_assoc($acesso)) { ?>
								<div style="float: left">
									<label for="<?php echo $dados["marca"]; ?>" style="margin-right: 20px; float: left;">
										<input type="checkbox" name="marcas_consumidas[]" id="<?php echo $dados["marca"]; ?>" value="<?php echo $dados["marca"]; ?>" style="width: 5px; float: left;" />
										<?php echo utf8_encode($dados["marca"]); ?>
									</label><br>
								</div>
							<?php } ?>
							<div>
								<label for="Outras" style="margin-right: 20px; float: left;">
									<input type="checkbox" name="marcas_consumidas[]" id="Outras" value="Outras" style="width: 5px; float: left;" />Outras
								</label><br>
							</div><br>
							<div>
								<label for="sabores_consumidos_outros">Se outras, favor indicar quais: </label>
								<input type="text" name="marcas_consumidas_outras">
							</div>
			</div><br>

			<div>
				<p>Qual é sua marca preferida de <?php echo strtolower(utf8_encode($categoria)); ?>? </p>
				<div>
					<select name="marca_preferida" id="marca_preferida" >
						<?php $consulta = "SELECT * FROM marcas WHERE categoria_id = {$_SESSION["categoria_id"]}";
						$acesso = mysqli_query($conecta, $consulta);
						while ($dados = mysqli_fetch_assoc($acesso)) { ?>
							<option value="<?php echo utf8_encode($dados["marca"]); ?>"><?php echo utf8_encode($dados["marca"]); ?></option>
						<?php } ?>
							<option value="Outra">Outra</option>
					</select>
				</div>
				<div>
					<label for="marca_preferida_outra">Se outro, favor indicar qual: </label>
					<input type="text" name="marca_preferida_outra">
				</div>
			</div><br><br>

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
