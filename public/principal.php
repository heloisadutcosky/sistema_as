<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$funcao_temp = isset($_GET["funcao"]) ? $_GET["funcao"] : $_SESSION["funcao"];
	$_SESSION["teste"] = isset($_GET["teste"]) ? $_GET["teste"] : 0;
	$corrigir = isset($_GET["corrigir"]) ? 1 : 0;

	// Setar projeto e categoria
	if (isset($_GET["codigo"])) {
		$_SESSION["projeto_id"] = $_GET["codigo"];
		echo $_SESSION["projeto_id"];

		$consulta = "SELECT * FROM projetos WHERE projeto_id = {$_SESSION["projeto_id"]}"; 
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);

		$_SESSION["produto"] = $dados["produto"];
		$_SESSION["categoria_id"] = $dados["categoria_id"];
		$_SESSION["tipo_avaliador"] = strtolower($dados["tipo_avaliador"]);
		$_SESSION["tipo_avaliacao"] = $dados["tipo_avaliacao"];
		$consumo = $dados["consumo_ativo"];

		echo "location:{$_SESSION["tipo_avaliador"]}/{$_SESSION["tipo_avaliacao"]}/principal.php";

		$consulta2 = "SELECT * FROM categorias WHERE categoria_id = {$_SESSION["categoria_id"]}"; 
		$acesso2 = mysqli_query($conecta, $consulta2);
		$dados2 = mysqli_fetch_assoc($acesso2);
		$_SESSION["categoria"] = $dados2["categoria"];

		// Redirecionar
		$consulta3 = "SELECT * FROM result_consumo WHERE categoria_id = {$_SESSION["categoria_id"]} AND user_id = {$_SESSION["user_id"]}";
		$acesso3 = mysqli_query($conecta, $consulta3);
		$preenchida = mysqli_fetch_assoc($acesso3);

		if ((empty($preenchida) && $consumo == 1) || ($_SESSION["teste"]==1 && $consumo == 1)) {
			header("location:consumo.php");
		} else {
			header("location:{$caminho}public/{$_SESSION["tipo_avaliador"]}/{$_SESSION["tipo_avaliacao"]}/principal.php");
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

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<p>Muito bem vindo(a), <?php echo $_SESSION["usuario"]; ?>! 
				<?php if ($corrigir==1) {
					$_SESSION["correcao"]=1; ?>
					<u>Em que projeto você deseja retornar?</u>
				<?php } else { ?>
					<u>Qual será a avaliação que você vai realizar hoje?</u>
				<?php } ?>
			</p>
		</article>

		<nav>
			<ul style="list-style: none;">
			<?php 

				$consulta = "SELECT * FROM projetos WHERE form_ativo = 1 AND tipo_avaliador = '{$funcao_temp}'";
				$acesso = mysqli_query($conecta, $consulta);
				$rows = mysqli_num_rows($acesso);

				if ($rows == 1) {
					$dados = mysqli_fetch_assoc($acesso);
					$_SESSION["form"] = $dados["nome_form"];
					//header("location:principal.php?codigo={$dados["projeto_id"]}&funcao={$funcao_temp}&teste={$_SESSION["teste"]}");
				}

				$algum=0;
				while($linha = mysqli_fetch_assoc($acesso)) { 
					$consulta2 = "SELECT * FROM amostras WHERE projeto_id = {$linha["projeto_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
					$n_amostras = mysqli_num_rows($acesso2);
					mysqli_free_result($acesso2);

					$consulta2 = "SELECT * FROM formularios WHERE projeto_id = {$linha["projeto_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
					$n_atributos = mysqli_num_rows($acesso2);
					mysqli_free_result($acesso2);

					$consulta2 = "SELECT * FROM resultados WHERE projeto_id = {$linha["projeto_id"]} AND user_id = {$_SESSION["user_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
						if ((mysqli_num_rows($acesso2) != $n_amostras*$n_atributos) || $corrigir==1) { 
							$algum = 1; ?>
						
						<img src="
						<?php echo utf8_encode($linha["url_imagem"]); ?>
						" width="100" height="75" style="float: left;"><br><br>
						<li class="menu"><a href="principal.php?codigo=<?php echo $linha["projeto_id"]; ?>&funcao=<?php echo $funcao_temp; ?>&teste=<?php echo $_SESSION["teste"]; ?>"><?php echo utf8_encode($linha["nome_form"]); ?></a></li><br><br>
					<?php } ?>
				<?php } ?>

			<?php if ($algum==0) { ?>
				<li class="menu"><a href="principal.php?corrigir=1&funcao=<?php echo $funcao_temp; ?>&teste=<?php echo $_SESSION["teste"]; ?>">Corrigir notas</a></li>
			<?php } ?>
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