<?php

	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "cadastro";
	$atributo_id = isset($_GET["atributo"]) ? $_GET["atributo"] : 0;

	// Informação de número de formulários relacionados ao projeto
	if (isset($_POST["n_mais"])) {
		$n_escalas = $_POST["n_escalas"]+1;
	} else if (isset($_POST["n_menos"])) {
		$n = $_POST["n_menos"];
		if (!empty($_POST["escala{$n}"])) {
			$excluir = "DELETE FROM opcoes WHERE atributo_id = {$atributo_id} AND escala = {$_POST["escala{$n}"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			$n_escalas = $_POST["n_escalas"]-1;
		}
	} else if (isset($_POST["n_escalas"])) { 
		$n_escalas = $_POST["n_escalas"];
	} else {
		$n_escalas = 1;
	}

	// Consultar atributos
	$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
	$acesso = mysqli_query($conecta, $consulta);

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	// Consultar opções
	$consulta2 = "SELECT * FROM opcoes WHERE atributo_id = {$atributo_id}";
	$acesso2 = mysqli_query($conecta, $consulta2);
	// ------------------------------------------------------------------------------

	$conjunto_atributos = isset($_POST["conjunto_atributos"]) ? utf8_decode($_POST["conjunto_atributos"]) : $dados["conjunto_atributos"];
	$descricao_conjunto = isset($_POST["descricao_conjunto"]) ? utf8_decode($_POST["descricao_conjunto"]) : $dados["descricao_conjunto"];

	if (isset($_POST["atributo"])) {
		$atributo = utf8_decode($_POST["atributo"]);
		$definicao_atributo = utf8_decode($_POST["definicao_atributo"]);
		$disposicao_pergunta = $_POST["disposicao_pergunta"];
		$atributo_completo_port = utf8_decode($_POST["atributo_completo_port"]);
		$atributo_completo_eng = utf8_decode($_POST["atributo_completo_eng"]);


		if (isset($_POST["completo"]) || isset($_POST["novo_conjunto"])) {
		// Alterar cadastro ---------------------------------------------------------
		if ($acao == "alteracao") {
				
			$alterar = "UPDATE atributos SET formulario_id = {$_SESSION["formulario_id"]}, conjunto_atributos = '{$conjunto_atributos}', descricao_conjunto = '{$descricao_conjunto}', atributo = '{$atributo}', definicao_atributo = '{$definicao_atributo}', disposicao_pergunta = '{$disposicao_pergunta}', atributo_completo_port = '{$atributo_completo_port}', atributo_completo_eng = '{$atributo_completo_eng}' WHERE atributo_id = {$atributo_id}";

			$operacao_alterar = mysqli_query($conecta, $alterar);

			if (!$operacao_alterar) {
				echo $alterar;
				die("Falha na alteração dos dados.");
			} 
		}
		// --------------------------------------------------------------------------

		// Cadastrar ----------------------------------------------------------------
		if ($acao == "cadastro") {

			// Verificar existência do atributo na base ------------------------------

			$consulta_atributo = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_completo_eng = '{$atributo_completo_eng}'";

			$acesso = mysqli_query($conecta, $consulta_atributo);
			$existe_atributo = mysqli_fetch_assoc($acesso);

			if (!empty($existe_atributo)) { 
				$atributo_id_temp = 0; ?>
				<p>Esse atributo já foi cadastrado nesse projeto</p>
			<?php } 

			// ----------------------------------------------------------------------
				
			else {
				$cadastrar = "INSERT INTO atributos (formulario_id, conjunto_atributos, descricao_conjunto, atributo, definicao_atributo, disposicao_pergunta, atributo_completo_eng, atributo_completo_port) VALUES ({$_SESSION["formulario_id"]}, '$conjunto_atributos', '$descricao_conjunto', '$atributo', '$definicao_atributo', '$disposicao_pergunta', '$atributo_completo_eng', '$atributo_completo_port')";

				$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

				if (!$operacao_cadastrar) {
				} else {
					$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_completo_eng = '{$atributo_completo_eng}'";
					$acesso = mysqli_query($conecta, $consulta);
					$dados_opcoes = mysqli_fetch_assoc($acesso);
					$atributo_id = $dados_opcoes["atributo_id"];
				}
			}
		}
		// --------------------------------------------------------------------------

		// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM atributos WHERE atributo_id = {$atributo_id}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} 
		}
		// --------------------------------------------------------------------------
	}
	// ------------------------------------------------------------------------------
} else {
	$consulta = "SELECT * FROM atributos WHERE atributo_id = {$atributo_id}";
	$acesso = mysqli_query($conecta, $consulta);

	$dados = mysqli_fetch_assoc($acesso);
	$atributo = $dados["atributo"];
	$definicao_atributo = $dados["definicao_atributo"];
	$disposicao_pergunta = $dados["disposicao_pergunta"];
	$atributo_completo_port = $dados["atributo_completo_port"];
	$atributo_completo_eng = $dados["atributo_completo_eng"];
}

if (isset($_POST["completo"])) {
	$atributo = "";
	$definicao_atributo = "";
	$disposicao_pergunta = "";
	$atributo_completo_port = "";
	$atributo_completo_eng = "";
}

	// Informações preenchidas ------------------------------------------------------
	$consulta_opcao = "SELECT * FROM opcoes WHERE atributo_id = {$atributo_id}";
	$acesso_opcao = mysqli_query($conecta, $consulta_opcao);

	if (isset($_POST["completo"]) || isset($_POST["novo_conjunto"])) {
	
	$n = 1;
	while ($n <= $n_escalas) {
		if (isset($_POST["escala{$n}"])) {
			$opcao = mysqli_fetch_assoc($acesso_opcao);
			$texto = utf8_decode($_POST["texto{$n}"]);
			$escala = !empty($_POST["escala{$n}"]) ? $_POST["escala{$n}"] : 0;
			$referencia = "";
			$imagem = "";

			// Cadastrar ----------------------------------------------------------------
			if (($acao == "cadastro" || $acao == "alteracao") && $atributo_id <> 0) {

				// Verificar existência do atributo na base ------------------------------

				$consulta_atributo = "SELECT * FROM opcoes WHERE atributo_id = {$atributo_id} AND escala = {$escala} AND texto = '{$texto}'";

				$acesso = mysqli_query($conecta, $consulta_atributo);
				$existe_atributo = mysqli_fetch_assoc($acesso);

				if (!empty($existe_atributo)) { 
					$alterar = "UPDATE opcoes SET escala = '{$escala}', texto = '{$texto}', referencia = '{$referencia}', imagem = '{$imagem}' WHERE opcao_id = {$opcao["opcao_id"]}";

				$operacao_alterar = mysqli_query($conecta, $alterar);
				} 

				// ----------------------------------------------------------------------
					
				else {
					$cadastrar = "INSERT INTO opcoes (atributo_id, escala, texto, referencia, imagem) VALUES ($atributo_id, '$escala', '$texto', '$referencia', '$imagem')";

					$operacao_cadastrar = mysqli_query($conecta, $cadastrar);
				}

				if (isset($_POST["novo_conjunto"])) {
					header("location:form_{$_SESSION["tipo_formulario"]}.php?acao=cadastro");
				} 

				if ($acao == "alteracao") {
					header("location:dados.php");
				}
			}
			// --------------------------------------------------------------------------

			// Excluir cadastro ---------------------------------------------------------
			if ($acao == "exclusao") {
					
				$excluir = "DELETE FROM opcoes WHERE atributo_id = {$atributo_id}";

				$operacao_excluir = mysqli_query($conecta, $excluir);

				if (!$operacao_excluir) {
					echo $excluir;
					die("Falha na exclusão dos dados.");
				} else {
					header("location:dados.php");
				}
			}
			// --------------------------------------------------------------------------
		}
		$n = $n + 1;
	}
}

if (($acao == "cadastro") && (isset($_POST["completo"]) || isset($_POST["novo_conjunto"]))) {
	$atributo_id = 0;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Atributos</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2><?php 
			if ($acao == "alteracao") echo "Alteração de ";
			elseif ($acao == "exclusao") echo "Exclusão de ";
			else echo "Cadastro de Atributo - " . $_SESSION["nome_formulario"]; 
			?></h2>
		
		<form action="?acao=<?php echo $acao; ?>&atributo=<?php echo $atributo_id; ?>" method="post">

			<div style="background-color: #F8F8F8; padding: 5px 5px 15px 5px; width: 600px">
				<p style="margin-left: 10px; float: left; margin-right: 10px"><b>Conjunto de atributos: </b></p>

				<div style="margin-top: 15px">
					<input type="text" id="conjunto_atributos" name="conjunto_atributos" value="<?php echo utf8_encode($conjunto_atributos); ?>" style= "width: 370px;">
				</div><br>

				<div>
					<label for="descricao_conjunto">Explicação de como avaliá-lo: </label>
					<input type="text" id="descricao_conjunto" name="descricao_conjunto" value="<?php echo utf8_encode($descricao_conjunto); ?>" style= "width: 550px; height: 40px;">
				</div>
			</div><br>
			
			<div style="background-color: #F8F8F8; padding: 5px 5px 15px 5px; width: 600px">
				<p style="margin-left: 10px; float: left; margin-right: 10px"><b>Atributo:</b> </p>
				
				<div style="margin-top: 15px">
					<input type="text" id="atributo" name="atributo" value="<?php echo utf8_encode($atributo); ?>" style= "width: 470px;" required>
				</div><br>

				<div>
					<label for="definicao_atributo">Pergunta relacionada ao atributo: </label>
					<input type="text" id="definicao_atributo" name="definicao_atributo" value="<?php echo utf8_encode($definicao_atributo); ?>" style= "width: 550px; height: 40px;">
				</div><br>

				<div>
					<label for="disposicao_pergunta">Disposição da pergunta no formulário: </label>
					<select id="disposicao_pergunta" name="disposicao_pergunta" style= "width: 550px;">
						<option></option>
						<option value="text" <?php if ($disposicao_pergunta == "text") { ?>
							selected
						<?php } ?>>Texto livre</option>
						<option value="select" <?php if ($disposicao_pergunta == "select") { ?>
							selected
						<?php } ?>>Seleção vertical (dropdown)</option>
						<option value="lista" <?php if ($disposicao_pergunta == "lista") { ?>
							selected
						<?php } ?>>Seleção horizontal (quadradinhos)</option>
						<option value="checkbox" <?php if ($disposicao_pergunta == "checkbox") { ?>
							selected
						<?php } ?>>Checkbox</option>
					</select>
				</div><br>

				<div style="margin-bottom: 1px;">
					<div style="float: left; margin-right: 30px;">
						<label for="atributo_completo_port">Nome completo em português<small><sup>*</sup></small>:</label>
						<input type="text" id="atributo_completo_port" name="atributo_completo_port" value="<?php echo $atributo_completo_port; ?>" style="width: 250px; margin-bottom: 10px;" required>
					</div>

					<div>
						<label for="atributo_completo_eng">Nome completo em inglês<small><sup>*</sup></small>:</label>
						<input type="text" id="atributo_completo_eng" name="atributo_completo_eng" value="<?php echo $atributo_completo_eng; ?>" style="width: 250px; margin-bottom: 10px;">
					</div>
					<small style="font-size: 55%; margin-left: 10px; width: 200px"><sup>*</sup>Nomes que aparecerão na planilha de resultados</small>
				</div>
			</div><br>

			<?php 
			$consulta = "SELECT * FROM opcoes WHERE atributo_id = {$atributo_id}";
			$acesso = mysqli_query($conecta, $consulta);
			if (($acao == "alteracao" || $acao == "exclusao") && (!isset($_POST["atributo"]))) {
				$n_escalas = mysqli_num_rows($acesso);
				if ($n_escalas==0) {
					$n_escalas=1;
				}
			}

			$n = 1;
			while ($n <= $n_escalas) {

				$n_post = $n;
				if (isset($_POST["n_menos"])) {
					if ($n>=$_POST["n_menos"]) {
						$n_post = $n+1;
					}
				}

				if (isset($_POST["escala{$n}"])) {
						$dados = mysqli_fetch_assoc($acesso);
						$escala = $_POST["escala{$n_post}"];
						$texto = $_POST["texto{$n_post}"];
					} else {
						$dados = mysqli_fetch_assoc($acesso);
						$escala = $dados["escala"];
						$texto = utf8_encode($dados["texto"]);
					}

					if (isset($_POST["completo"])) {
						$escala = "";
						$texto = "";
						$n_escalas = 1;
					}
			?>
				<div style="background-color: #F8F8F8; padding: 20px 5px 0px 5px; width: 600px">
					<div>
						<div style="float: left; margin-right: 20px;">
							<label for="escala">Valor: </label>
							<input type="number" id="escala" name="escala<?php echo($n); ?>" value="<?php echo $escala; ?>" style="width: 70px;">
						</div>
						<div>
							<label for="texto">Texto: </label>
							<input type="text" id="texto" name="texto<?php echo($n); ?>" value="<?php echo $texto; ?>" style="width: 330px;">
						</div>
					</div><br>

					<input type="hidden" name="n_escalas" value="<?php echo($n_escalas); ?>">

					<div style="float: right; margin-top: -60px; margin-right: 40px">
						<button name="n_menos" type="submit" value="<?php echo $n; ?>" style="width: 40px; margin-top: 10px; font-size: 120%; background-color: #FFF; color: #778899; text-align: center; padding: 0px; float: left; margin-right: 7px">-</button>
						<?php if ($n == $n_escalas) { ?>
							<button name="n_mais" type="submit" value="<?php echo $n; ?>" style="width: 40px; margin-top: 10px; font-size: 120%; background-color: #FFF; color: #778899; text-align: center; padding: 0px">+</button>
						<?php } ?>
					</div>
				</div><br>
			<?php $n = $n + 1;
			} ?>
			
			<br><br>
			<input type="submit" id="botao" name="completo" value="<?php 
				if ($acao == "alteracao") echo "Alterar atributo";
				elseif ($acao == "exclusao") echo "Excluir atributo";
				else echo "Cadastrar atributo";
			?>" style="margin-left: 5px; float: left; margin-right: 200px">

			<?php if ($acao == "cadastro") { ?>
				<input type="submit" id="botao" name="novo_conjunto" value="Cadastrar e abrir novo conjunto" style="background-color: #FFF; color: #778899; padding-left: 5px; padding-right: 5px">
			<?php } ?>
		</form>
		<br><br>
		</article><br><br>

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
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
if (isset($acesso2)) {
	mysqli_free_result($acesso2);
}
	mysqli_close($conecta);
?>