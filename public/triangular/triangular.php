<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	if (isset($_GET["amostra"])) { 
		$amostra_escolhida = $_GET["amostra"];
	} else {
		$amostra_escolhida = 0;
	}

	if (isset($_POST["amostra"])) { 
		$amostra = $_POST["amostra"];
		$nota = $_POST["amostra"];
		$justificativa = utf8_decode($_POST["justificativa"]);

		$consulta_resultados = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]}";
		echo $consulta_resultados;
		$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
		$resultados = mysqli_fetch_assoc($acesso_resultados);

		$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);
		$atributo_id = $dados["atributo_id"];
		$atributo_completo_eng = $dados["atributo_completo_eng"];
		$atributo_completo_port = $dados["atributo_completo_port"];

		if (empty($resultados)) {
			$inserir = "INSERT INTO tb_resultados (projeto_id, formulario_id, sessao, user_id, amostra_codigo, atributo_id, atributo_completo_eng, atributo_completo_port, justificativa, nota, teste) VALUES ({$_SESSION["projeto_id"]}, {$_SESSION["formulario_id"]}, {$_SESSION["sessao"]}, {$_SESSION["user_id"]}, '{$amostra}', {$atributo_id}, '{$atributo_completo_eng}', '{$atributo_completo_port}', '{$justificativa}', {$nota}, {$_SESSION["teste"]})";
			echo $inserir;

			$operacao_inserir = mysqli_query($conecta, $inserir);
		} else {

			$alterar = "UPDATE tb_resultados SET nota = {$nota}, amostra_codigo = '{$amostra}', justificativa = '{$justificativa}' WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]}";

			$operacao_alterar = mysqli_query($conecta, $alterar);
		}

		header("location:../principal.php?codigo={$_SESSION["projeto_id"]}");
	} 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<style type="text/css">
		.menu {
			display: inline-block;
		}
		.menu2 {
			display: inline-block;
		}
		li.menu2 a {
		  text-decoration: none;
		  background-color: #FFF;
		  margin-bottom: 1px;
		  padding: 2px 12px;
		  color: #C2534B;
		  border: 1px solid #B8B8B8;
		}

		li.menu2 a:hover {
		  background-color: #F99B95;
		  margin: 0 auto;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		

		<?php 
		$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);
		?>

			<article style="margin-left: 20px">
				<form action="triangular.php" method="post">
					<h2><?php echo $_SESSION["produto"]; ?></h2>
					<p style="margin-bottom: 30px; width: 850px"><?php echo utf8_encode($dados["definicao_atributo"]); ?></p>
					<nav>
						<ul>
							<?php foreach ($_SESSION["amostras"] as $amostra) { ?>
								<li class="menu"><a href="triangular.php?amostra=<?php echo $amostra; ?>" style="<?php if ($amostra == $amostra_escolhida) {echo "background-color: #F99B95"; }?>">Amostra <?php echo $amostra; ?></a></li>
							<?php } ?>
						</ul>
					</nav><br>

					<input type="hidden" name="amostra" value="<?php echo $amostra_escolhida; ?>">

					<div>
						<p>O que você percebeu de diferente na amostra? Por favor, descreva:</p>
						<input type="text" name="justificativa" style="width: 800px; height: 40px">
					</div><br><br>

					<input type="submit" name="completo" id="botao" value="Confirmar"></input>
					<br><br>
				</form>
			</article>

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