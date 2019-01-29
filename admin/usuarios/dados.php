<?php 
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once("../../_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once("../../conexao/conexao.php");

	$consulta = "SELECT * FROM usuarios";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Projetos About Solution</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../../_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="../../_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="../../_css/estilo_tabelas_topo.css">

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
		<?php include_once("../../_incluir/topo.php"); ?>
		<h2 class="espaco">USUÁRIOS ABOUT SOLUTION</h2>

		<div id="cima_tabela" class="usuarios">
			<ul>
			    <li><b>CPF</b></li>
			    <li><b>Nome</b></li>
			    <li><b>Classificação</b></li>
			</ul>
		</div>
		<div id="janela" class="usuarios">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li><?php echo utf8_encode($linha["cpf"]) ?></li>
			    <li><?php echo utf8_encode($linha["nome"]) ?></li>
			    <li><?php echo utf8_encode($linha["funcao"]) ?></li>
			    <li><a href="alteracao.php?codigo=<?php echo $linha["user_id"] ?>">Alterar</a> </li>
			    <li><a href="exclusao.php?codigo=<?php echo $linha["user_id"] ?>">Excluir</a> </li>
			</ul>
			<?php
			    }
			?>
		</div>
		

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>
		<br>
		
		<?php include_once("../../_incluir/rodape.php"); ?>

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