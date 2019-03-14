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

		$consulta = "SELECT * FROM projetos WHERE projeto_id = {$_SESSION["projeto_id"]}"; 
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);

		$_SESSION["produto_id"] = $dados["produto_id"];
		$_SESSION["categoria_id"] = $dados["categoria_id"];
		$_SESSION["tipo_avaliador"] = strtolower($dados["tipo_avaliador"]);
		$_SESSION["tipo_avaliacao"] = $dados["tipo_avaliacao"];
		$consumo = $dados["consumo_ativo"];

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
			header("location:{$caminho}public/{$_SESSION["tipo_avaliador"]}/{$_SESSION["tipo_avaliacao"]}/principal.php?corrigir={$corrigir}");
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

		<article>
			
				<?php if ($corrigir==1) { ?>
					<p><u>Em que projeto você deseja retornar?</u></p>
				<?php } else { ?>
					<p>Muito bem vindo(a), <?php echo $_SESSION["usuario"]; ?>! 
					<u>Qual será a avaliação que você vai realizar hoje?</u>
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
					$consulta2 = "SELECT * FROM tb_amostras WHERE projeto_id = {$linha["projeto_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
					$n_amostras = mysqli_num_rows($acesso2);
					mysqli_free_result($acesso2);

					
					$consulta2 = "SELECT * FROM avaliacoes WHERE projeto_id = {$linha["projeto_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);

					$n_atributos = 0;
					while ($dados = mysqli_fetch_assoc($acesso2)) {
						$consulta3 = "SELECT * FROM atributos WHERE formulario_id = {$dados["formulario_id"]}";
						$acesso3 = mysqli_query($conecta, $consulta3);
						$n_atributos = $n_atributos + mysqli_num_rows($acesso3);
						mysqli_free_result($acesso3);
					}
					

					$consulta2 = "SELECT * FROM resultados WHERE projeto_id = {$linha["projeto_id"]} AND user_id = {$_SESSION["user_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
						if ((mysqli_num_rows($acesso2) != $n_amostras*$n_atributos) || $corrigir==1) { 
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