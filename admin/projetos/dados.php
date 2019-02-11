<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$consulta = "SELECT * FROM projetos";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Projetos About Solution</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">

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
		<h2 class="espaco">PROJETOS ABOUT SOLUTION</h2>
		<br>

		<div class="botao">
			<a class="espaco" href="painel.php?acao=cadastro">Adicionar projeto</a><br>
		</div>
		<br>
		
		<div id="cima_tabela" style="width: 675px;">
			<ul>
			    <li style="width:80px;"><b>Empresa</b></li>
			    <li><b>Tipo de teste</b></li>
			    <li><b>Produto</b></li>
			</ul>
		</div>
		<div id="janela" style="width: 675px;">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li style="width:80px;"><?php echo utf8_encode($linha["empresa"]) ?></li>
			    <li><?php echo utf8_encode($linha["tipo_avaliacao"]) ?></li>
			    <li style="width: 110px;"><?php 
			    $consulta = "SELECT * FROM produtos WHERE produto_id = {$linha["produto_id"]}";
				$acesso2 = mysqli_query($conecta, $consulta);
				$dados = mysqli_fetch_assoc($acesso2);
				$produto = $dados["produto"];
			    echo utf8_encode($dados["produto"]) ?></li>
			    <li><a href="formulario/dados.php?codigo=<?php echo $linha["projeto_id"]; ?>&produto=<?php echo $produto; ?>">Formulário</a> </li>
			    <li><a href="sessoes/dados.php?codigo=<?php echo $linha["projeto_id"] ?>&produto=<?php echo $produto; ?>">Sessão</a> </li>
			    <li><a href="painel.php?acao=alteracao&codigo=<?php echo $linha["projeto_id"] ?>">Alterar</a> </li>
			    <li><a href="painel.php?acao=exclusao&codigo=<?php echo $linha["projeto_id"] ?>">Excluir</a> </li>
			</ul>
			<?php
			    }
			?>
		</div>
		<br>
		</article>

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div><br><br>
		
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
