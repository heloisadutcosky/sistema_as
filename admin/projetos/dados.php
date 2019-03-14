<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$consulta = "SELECT * FROM tb_projetos";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Projetos</title>
	
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
		
		<div id="cima_tabela" style="width: 630px;">
			<ul>
			    <li style="width:120px;"><b>Empresa</b></li>
			    <li style="width:180px;"><b>Produto</b></li>
			    <li style="width:90px;"><b>Início</b></li>
			    <li style="width:90px;"><b>Fim</b></li>
			</ul>
		</div>
		<div id="janela" style="width: 630px;">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li style="width:120px;">
			    	<?php 
					$consulta_empresas = "SELECT * FROM empresas WHERE empresa_id = {$linha["empresa_id"]}";
					$acesso_empresas = mysqli_query($conecta, $consulta_empresas);
					$empresa = mysqli_fetch_assoc($acesso_empresas);
					echo utf8_encode($empresa["nome_fantasia"]); ?></li>
			    <li style="width: 180px;"><?php 
			    	if ($linha["produto_id"] == 0) {
			    		$consulta2 = "SELECT * FROM categorias WHERE categoria_id={$linha["categoria_id"]}";
						$acesso2 = mysqli_query($conecta, $consulta2);
						$dados = mysqli_fetch_assoc($acesso2);
						$produto = $dados["categoria"];
			    	} else {
			    		$consulta2 = "SELECT * FROM produtos WHERE produto_id={$linha["produto_id"]}";
						$acesso2 = mysqli_query($conecta, $consulta2);
						$dados = mysqli_fetch_assoc($acesso2);
						$produto = $dados["produto"];
			    	}
			    	
					echo utf8_encode($produto); ?></li>
			    <li style="width:90px;"><?php echo $linha["data_inicio"]; ?></li>
			    <li style="width:90px;"><?php echo $linha["data_fim"]; ?></li>
			    
			    <li style="width:50px;"><a href="painel.php?acao=alteracao&codigo=<?php echo $linha["projeto_id"]; ?>">Alterar</a> </li>
			    <li style="width:50px;"><a href="painel.php?acao=exclusao&codigo=<?php echo $linha["projeto_id"]; ?>">Excluir</a> </li>
			</ul>
			<?php
			    }
			?>
		</div>
		<br><br><br><br>
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
