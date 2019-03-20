<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$funcao_temp = isset($_GET["funcao"]) ? $_GET["funcao"] : $_SESSION["funcao"];
	$_SESSION["teste"] = isset($_GET["teste"]) ? $_GET["teste"] : 0;

	$corrigir = isset($_GET["corrigir"]) ? $_GET["corrigir"] : 0;

	// Setar projeto e categoria
	if (isset($_GET["codigo"])) {
		$_SESSION["projeto_id"] = $_GET["codigo"];

		$consulta = "SELECT * FROM tb_projetos WHERE projeto_id = {$_SESSION["projeto_id"]}"; 
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);

		$_SESSION["produto_id"] = $dados["produto_id"];
		$_SESSION["categoria_id"] = $dados["categoria_id"];

		$consulta2 = "SELECT * FROM categorias WHERE categoria_id = {$_SESSION["categoria_id"]}"; 
		$acesso2 = mysqli_query($conecta, $consulta2);
		$dados2 = mysqli_fetch_assoc($acesso2);
		$_SESSION["categoria"] = $dados2["categoria"];

		$consulta2 = "SELECT * FROM produtos WHERE produto_id = {$_SESSION["produto_id"]}"; 
		$acesso2 = mysqli_query($conecta, $consulta2);
		$dados2 = mysqli_fetch_assoc($acesso2);
		$_SESSION["produto"] = $dados2["produto"];


		// Redirecionar
		$consulta = "SELECT * FROM avaliacoes WHERE form_ativo = 1 AND projeto_id = {$_SESSION["projeto_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		$rows = mysqli_num_rows($acesso);

		$_SESSION["formulario_id"] = array();
		$_SESSION["tipo_avaliador"] = array();
		$_SESSION["tipo_avaliacao"] = array();
		while($linha = mysqli_fetch_assoc($acesso)) { 
			$consulta2 = "SELECT * FROM tb_amostras WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$linha["formulario_id"]}";
			$acesso2 = mysqli_query($conecta, $consulta2);
			$n_amostras = mysqli_num_rows($acesso2);

			if (in_array($linha["tipo_avaliacao"], array("hedonica", "cata", "ideal")) && $n_amostras<>0) {
				$n_amostras_hedonica = $n_amostras;
			}

			if ($n_amostras == 0) {
				if (in_array($linha["tipo_avaliacao"], array("hedonica", "cata", "ideal"))) {
					$n_amostras = $n_amostras_hedonica;
				} else {
					$n_amostras = 1;
				}
			}
			mysqli_free_result($acesso2);


			$consulta2 = "SELECT * FROM atributos WHERE formulario_id = {$linha["formulario_id"]}";
			$acesso2 = mysqli_query($conecta, $consulta2);
			$n_atributos = mysqli_num_rows($acesso2);
			mysqli_free_result($acesso2);
			

			$consulta2 = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$linha["formulario_id"]} AND user_id = {$_SESSION["user_id"]}";
			$acesso2 = mysqli_query($conecta, $consulta2);
			$n_resultados = mysqli_num_rows($acesso2);
					//echo $linha["tipo_avaliacao"] . "<br>";
					//echo "resultados = " . $n_resultados . "<br>";
					//echo "amostras = " . $n_amostras . "<br>";
					//echo "atributos = " . $n_atributos . "<br>";

				if (($n_resultados != $n_amostras*$n_atributos) && $corrigir==0 && $linha["tipo_avaliacao"] <> "triangular") { 
					$_SESSION["formulario_id"][$linha["tipo_avaliacao"]] = $linha["formulario_id"];
					$_SESSION["tipo_avaliador"][$linha["tipo_avaliacao"]] = $linha["tipo_avaliador"];
					$_SESSION["tipo_avaliacao"][] = $linha["tipo_avaliacao"];
					//echo "{$caminho}public/{$_SESSION["tipo_avaliacao"]}/principal.php" . "<br>";
					
				} elseif (($n_resultados != $n_atributos) && $corrigir==0 && $linha["tipo_avaliacao"] == "triangular") {
					$_SESSION["formulario_id"][$linha["tipo_avaliacao"]] = $linha["formulario_id"];
					$_SESSION["tipo_avaliador"][$linha["tipo_avaliacao"]] = $linha["tipo_avaliador"];
					$_SESSION["tipo_avaliacao"][] = $linha["tipo_avaliacao"];
				}
			}
			print_r($_SESSION["tipo_avaliacao"]);
			if (!empty($_SESSION["tipo_avaliacao"])) {
				$tipo_avaliacao = $_SESSION["tipo_avaliacao"][0];
				$_SESSION["formulario_id"] = $_SESSION["formulario_id"][$tipo_avaliacao];
				$_SESSION["tipo_avaliador"] = $_SESSION["tipo_avaliador"][$tipo_avaliacao];
			
				header("location:{$caminho}public/{$tipo_avaliacao}/principal.php");
			}
	} 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<link rel="shortcut icon" href="/favicon.png" type="image/png">
	<link rel="shortcut icon" type="image/png" href="{$caminho}_incluir/trigo.png" />
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

	<style type="text/css">
		
		li.menu3 a {
		  text-decoration: none;
		  background-color: #F8F8F8;
		  margin-bottom: 1px;
		  padding: 2px 12px;
		  color: #686868;
		  border: 1px solid #B8B8B8;
		}

		li.menu3 a:hover {
		  background-color: #B8B8B8;
		  margin: 0 auto;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article style="margin-left: 30px">
			
				<?php if ($corrigir==1) { ?>
					<p><u>Em qual avaliação você deseja retornar?</u></p>
				<?php } else { ?>
					<p>Muito bem vindo(a), <?php echo $_SESSION["usuario"]; ?>! 
					<u>Qual produto você avaliará hoje?</u>
					</p>
				<?php } ?>
		</article>

		<nav>
			<ul style="list-style: none;">
			<?php 

				$consulta = "SELECT * FROM tb_projetos";
				$acesso = mysqli_query($conecta, $consulta);
				$rows = mysqli_num_rows($acesso);

				//if ($rows == 1) {
				//	$dados = mysqli_fetch_assoc($acesso);
				//	$_SESSION["form"] = $dados["nome_form"];
					//header("location:principal.php?codigo={$dados["projeto_id"]}&funcao={$funcao_temp}&teste={$_SESSION["teste"]}");
				//}


				$algum=0;
				while($linha = mysqli_fetch_assoc($acesso)) { 


						if (date("Y-m-d") > $linha["data_inicio"] && date("Y-m-d") < $linha["data_fim"]) { 
							$algum = 1; ?>
						
						<img src="
						<?php 
						if ($linha["produto_id"]<>0) {
							$consulta2 = "SELECT * FROM produtos WHERE produto_id = {$linha["produto_id"]}";
							$acesso2 = mysqli_query($conecta, $consulta2);
							$dados2 = mysqli_fetch_assoc($acesso2);
							$produto = $dados2["produto"];
						} else {
							$consulta2 = "SELECT * FROM categorias WHERE categoria_id = {$linha["categoria_id"]}";
							$acesso2 = mysqli_query($conecta, $consulta2);
							$dados2 = mysqli_fetch_assoc($acesso2);
							$produto = $dados2["categoria"];
						}
						echo utf8_encode($dados2["url_imagem"]); ?>
						" width="100" height="75" style="float: left;"><br><br>
						<li class="menu"><a href="principal.php?codigo=<?php echo $linha["projeto_id"]; ?>&funcao=<?php echo $funcao_temp; ?>&teste=<?php echo $_SESSION["teste"]; ?>&corrigir=<?php echo $corrigir; ?>"><?php echo utf8_encode($produto); ?></a></li><br><br>
					<?php } ?>
				<?php } ?>

			<?php //if ($algum==0) { ?>
				<br><br>
				<li class="menu3"><a href="principal.php?corrigir=1&funcao=<?php echo $funcao_temp; ?>&teste=<?php echo $_SESSION["teste"]; ?>" style="font-size: 90%;">Corrigir notas</a></li>
			<?php //} ?>
			</ul>
		</nav>
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
	mysqli_close($conecta);
?>