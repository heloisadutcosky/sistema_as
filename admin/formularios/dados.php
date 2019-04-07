<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$consulta = "SELECT * FROM tb_formularios";
	$acesso = mysqli_query($conecta, $consulta);

	$_SESSION["tipos_formulario"] = array("cata" => "CATA", "pdq" => "Painel descritivo quantitativo");

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
		<h2 class="espaco">FORMULÁRIOS</h2>
		<br>

		<div class="botao">
			<a class="espaco" href="painel.php?acao=cadastro">Adicionar formulário</a><br>
		</div>
		<br>
		
		<div id="cima_tabela" style="width: 650px;">
			<ul>
			    <li style="width: 120px;"><b>Categoria</b></li>
			    <li style="width: 140px;"><b>Produto</b></li>
			    <li style="width: 150px;"><b>Tipo de teste</b></li>
			</ul>
		</div>
		<div id="janela" style="width: 650px;">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
				<li style="width: 120px;"><?php 
			    	$consulta2 = "SELECT * FROM categorias WHERE categoria_id={$linha["categoria_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
					$dados = mysqli_fetch_assoc($acesso2);
					echo utf8_encode($dados["categoria"]); ?></li>
			    <li style="width: 140px;"><?php 
			    	$consulta2 = "SELECT * FROM produtos WHERE produto_id={$linha["produto_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
					$dados = mysqli_fetch_assoc($acesso2);
					echo utf8_encode($dados["produto"]); ?></li>
			    <li style="width: 180px;"><?php switch ($linha["tipo_formulario"]) {
			     	case 'consumo': ?>
			     		Consumo
			     	<?php break;
			     	case 'selecao_painel': ?>
			     		Seleção de painel
			     	<?php break;
			     	case 'pdq_aleatorizado': ?>
			     		Painel descritivo quantitativo - Aleatorização Manual
			     	<?php break;
			     	case 'cata': ?>
			     		CATA
			     	<?php break;
			     	case 'hedonica': ?>
			     		Escala hedônica
			     	<?php break;
			     	case 'ideal': ?>
			     		Escala do ideal
			     	<?php break;
			     	case 'triangular': ?>
			     		Teste triangular
			     	<?php break;
			     	default: ?>
			     		Painel descritivo quantitativo
			     	<?php break;
			     } ?></li>
			    <li style="width: 65px;"><a href="atributos/dados.php?formulario=<?php echo $linha["formulario_id"]; ?>">Atributos</a> </li>
			    <li style="width: 50px;"><a href="painel.php?acao=alteracao&codigo=<?php echo $linha["formulario_id"]; ?>">Alterar</a> </li>
			    <li style="width: 50px;"><a href="painel.php?acao=exclusao&codigo=<?php echo $linha["formulario_id"]; ?>">Excluir</a> </li>
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
