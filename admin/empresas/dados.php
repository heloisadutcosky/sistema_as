<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$consulta = "SELECT * FROM empresas";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Empresas</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">

	<style type="text/css">
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>
		<article>
			<h2 class="espaco" style="text-align: center; width: 630px">EMPRESAS CADASTRADAS</h2>
			<br>

			
			<div class="botao" style="float: left; margin-right: 200px;">
				<a href="painel.php?acao=cadastro">Cadastrar nova empresa</a>
			</div>

			<div class="botao">
				<a href="<?php echo($caminho); ?>_csv/tabelas.php?tabela=empresas" style="background-color: #FFF; color: #778899; padding-left: 45px; padding-right: 45px">Exportar dados</a>
			</div>
			<br>

			<div id="cima_tabela" style="width: 630px">
				<ul>
				    <li style="width: 120px; padding-left: 5px"><b>CNPJ</b></li>
				    <li style="width: 280px; padding-left: 5px"><b>Nome</b></li>
				    <li style="width: 70px"><b>Relação</b></li>
				</ul>
			</div>
			<div id="janela" style="width: 630px">
				<?php
				    while($linha = mysqli_fetch_assoc($acesso)) {
				?>
				<ul>
				    <li style="width: 120px; padding: 5px"><?php echo utf8_encode($linha["cnpj"]) ?></li>
				    <li style="width: 280px; padding: 5px"><?php echo utf8_encode($linha["razao_social"]) ?></li>
				    <li style="width: 70px;"><?php echo utf8_encode($linha["relacao"]) ?></li>
				    <li><a href="painel.php?acao=alteracao&codigo=<?php echo $linha["empresa_id"] ?>">Alterar</a> </li>
				    <li><a href="painel.php?acao=exclusao&codigo=<?php echo $linha["empresa_id"] ?>">Excluir</a> </li>
				</ul>
				<?php } ?>
			</div>
			<br><br><br><br><br><br>
		</article>

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>
		
		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 

	if (isset($acesso)) {
		// Liberar dados da memória
		mysqli_free_result($acesso);

		// Fechar conexão
		mysqli_close($conecta);
	}
	
?>