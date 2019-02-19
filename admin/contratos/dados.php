<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	if (isset($_GET["codigo"])) {
		$contrato_id = utf8_decode($_GET["codigo"]);
		$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";
	
	// Excluir cadastro ---------------------------------------------------------
		if ($acao == "exclusao") {
				
			$excluir = "DELETE FROM contratos WHERE contrato_id = {$contrato_id}";

			$operacao_excluir = mysqli_query($conecta, $excluir);

			if (!$operacao_excluir) {
				die("Falha na exclusão dos dados.");
			} else {
				header("location:dados.php");
			}
		}
	// --------------------------------------------------------------------------
	}

	$consulta = "SELECT * FROM contratos";
	$acesso = mysqli_query($conecta, $consulta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Usuários About Solution</title>
	
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
			<h2 class="espaco">CONTRATOS VIGENTES</h2>
			<br>

			
			<div class="botao">
				<a href="painel.php?acao=cadastro">Cadastrar novo contrato</a>
			</div>
			<br>

			<div id="cima_tabela" style="width: 500px">
				<ul>
				    <li style="width: 150px"><b>Empresa</b></li>
				    <li style="width: 100px"><b>Data Início</b></li>
				    <li style="width: 100px"><b>Data Fim</b></li>
				</ul>
			</div>
			<div id="janela" style="width: 500px">
				<?php
				    while($linha = mysqli_fetch_assoc($acesso)) {
				?>
				<ul>
				    <li style="width: 150px"><?php 
				    $consulta1 = "SELECT * FROM empresas WHERE empresa_id = {$linha["empresa_id"]}";
					$acesso1 = mysqli_query($conecta, $consulta1);
					$dados = mysqli_fetch_assoc($acesso1);
					echo $dados["nome_fantasia"];
				    ?></li>
				    <li style="width: 100px"><?php echo $linha["data_inicio"]; ?></li>
				    <li style="width: 100px"><?php echo $linha["data_fim"]; ?></li>
				    <li><a href="painel.php?acao=alteracao&codigo=<?php echo $linha["contrato_id"] ?>">Alterar</a> </li>
				    <li><a href="dados.php?acao=exclusao&codigo=<?php echo $linha["contrato_id"] ?>">Excluir</a> </li>
				</ul>
				<?php } ?>
			</div>
			<br><br><br><br><br><br><br><br>
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