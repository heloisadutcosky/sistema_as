<?php 

	$caminho =  "../../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	// Abrir consulta ao banco de dados
	if (isset($_GET["formulario"])) {
		$_SESSION["formulario_id"] = $_GET["formulario"];

		$consulta = "SELECT * FROM tb_formularios WHERE formulario_id = " . $_SESSION["formulario_id"];
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_fetch_assoc($acesso);

		$_SESSION["tipo_formulario"] = $dados["tipo_formulario"];
		$_SESSION["nome_formulario"] = $dados["nome_formulario"];
	}


	if (isset($_SESSION["formulario_id"])) {
		$consulta = "SELECT * FROM atributos WHERE formulario_id = " . $_SESSION["formulario_id"];
		$acesso = mysqli_query($conecta, $consulta);
		$dados = mysqli_num_rows($acesso);

		if ($dados>0) {

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Atributos</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco">Formulário - <?php echo $_SESSION["nome_formulario"]; ?></h2>
			<br>

			<div class="botao">
				<a href="form_<?php echo($_SESSION["tipo_formulario"]); ?>.php?acao=cadastro">Adicionar atributo</a>
			</div>
			<br>

			<div id="cima_tabela" style="width: 650px">
				<ul>
				    <li style="width: 105px"><b>Conjunto</b></li>
				    <li style="width: 180px"><b>Atributo</b></li>
				    <li style="width: 160px"><b>Nomes atributo</b></li>
				</ul>
			</div>
			<div id="janela" style="width: 650px">
				<?php
				    while($linha = mysqli_fetch_assoc($acesso)) {
				?>
				<ul>
				    <li style="width: 105px"><?php echo utf8_encode($linha["conjunto_atributos"]) ?></li>
				    <li style="width: 180px"><?php echo utf8_encode($linha["atributo"]) ?></li>
				    <li style="width: 160px"><?php echo utf8_encode($linha["atributo_completo_eng"]) ?></li>
				    <li style="width: 60px"><a href="form_<?php echo $_SESSION["tipo_formulario"]; ?>.php?acao=alteracao&atributo=<?php echo $linha["atributo_id"] ?>"><?php if(in_array($_SESSION["tipo_formulario"], array("ideal", "hedonica", "pdq"))) { echo "Opções"; } ?></a> </li>
				    <li style="width: 50px"><a href="form_<?php echo $_SESSION["tipo_formulario"]; ?>.php?acao=alteracao&atributo=<?php echo $linha["atributo_id"] ?>">Alterar</a> </li>
				    <li style="width: 50px"><a href="form_<?php echo $_SESSION["tipo_formulario"]; ?>.php?acao=exclusao&atributo=<?php echo $linha["atributo_id"] ?>">Excluir</a> </li>
				</ul>
				<?php } ?>	
			</div>
			<br><br><br><br><br><br>
		</article>

		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>
				
		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php } else { ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulários</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">

	<style type="text/css">
		li a {
	    list-style:none;
	    display:inline-block;
	    background-color: none;	    
	}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco">Formulário - <?php echo $_SESSION["nome_formulario"]; ?></h2>

			<p style="margin-left: 10px;">Nenhum atributo foi cadastrado para esse formulário</p><br><br>

			<div class="botao">
				<a href="form_<?php echo($_SESSION["tipo_formulario"]); ?>.php?acao=cadastro">Adicionar atributo</a>
			</div>
			<br><br><br><br><br><br><br><br><br><br>
		</article>

		<div class="direita">
			<a href="../dados.php">Voltar</a><br><br>
		</div>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>


<?php } 
} ?>
