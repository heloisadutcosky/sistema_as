<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$consulta = "SELECT * FROM usuarios";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Usuários</title>
	
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
			<h2 class="espaco" style="text-align: center; width: 630px">USUÁRIOS CADASTRADOS</h2>
			<br>

			
			<div class="botao" style="float: left; margin-right: 220px;">
				<a href="painel.php?acao=cadastro">Cadastrar novo usuário</a>
			</div>

			<div class="botao">
				<a href="<?php echo($caminho); ?>_csv/tabelas.php?tabela=usuarios" style="background-color: #FFF; color: #778899; padding-left: 40px; padding-right: 40px">Exportar dados</a>
			</div>
			<br>

			<div id="cima_tabela" style="width: 630px">
				<ul>
				    <li style="width: 120px; padding-left: 5px"><b>CPF</b></li>
				    <li style="width: 250px; padding-left: 5px"><b>Nome</b></li>
				    <li style="width: 100px; padding-left: 5px"><b>Classificação</b></li>
				</ul>
			</div>
			<div id="janela" style="width: 630px">
				<?php
				    while($linha = mysqli_fetch_assoc($acesso)) {
				?>
				<ul>
				    <li style="width: 120px; padding-left: 5px"><?php echo utf8_encode($linha["cpf"]) ?></li>
				    <li style="width: 250px; padding-left: 5px"><?php echo utf8_encode($linha["nome"]) ?></li>
				    <li style="width: 100px; padding-left: 5px"><?php echo utf8_encode($linha["funcao"]) ?></li>
				    <li><a href="painel.php?acao=alteracao&codigo=<?php echo $linha["user_id"] ?>">Alterar</a> </li>
				    <li><a href="painel.php?acao=exclusao&codigo=<?php echo $linha["user_id"] ?>">Excluir</a> </li>
				</ul>
				<?php } ?>
			</div>
			<br><br>
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