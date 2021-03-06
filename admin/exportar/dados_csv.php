<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores e administradores especiais)
	if (isset($_SESSION["user_id"])) {
			if ($_SESSION["funcao"] <> "Administrador" && $_SESSION["funcao"] <> "Administrador restrito") {
				header("location:{$caminho}public/principal.php");
			}
		} else {
			header("location:{$caminho}login.php");
		}


	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	if (isset($_GET["apagar"])) {
		$consulta = "DELETE FROM tb_resultados WHERE teste = 1 OR user_id=20";
		$acesso = mysqli_query($conecta, $consulta);

		header("location:dados_csv.php");
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Resultados</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_tabelas_topo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">
</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 style="margin-left: 20px">EXPORTAR RESULTADOS E FORMULÁRIOS DAS AVALIAÇÕES</h2>

			<div>
				<a href="dados_csv.php?apagar=1" style="background-color: #FFF; color: #778899; font-size: 75%; position: absolute; right: 75px;">Apagar dados de teste</a>
			</div><br>

			<div id="cima_tabela" style="width: 630px;">
				<ul>
				    <li style="width:300px;"><b>Projeto</b></li>
				</ul>
			</div>

			<div id="janela" style="width: 630px;">
				<?php
					$consulta = "SELECT * FROM tb_projetos";
					$acesso = mysqli_query($conecta, $consulta);
				    while($dados = mysqli_fetch_assoc($acesso)) {

				    	$consulta2 = "SELECT * FROM avaliacoes WHERE projeto_id = {$dados["projeto_id"]}";
				    	$acesso2 = mysqli_query($conecta, $consulta2);
				    	$linha = mysqli_fetch_assoc($acesso2);
				    	if ($linha["tipo_avaliacao"] == "pdq") {
				    		$avaliacao = "pdq";
				    	} else {
				    		$avaliacao = "livre";
				    	}

				?>
				<ul>
				    <li style="width:260px;">
				    	<?php 
						echo utf8_encode($dados["descricao_projeto"]); ?></li>
					<li style="width: 0px"></li>
					<li style="width: 0px"></li>
				    <li style="width:95px;"><a href="<?php echo $caminho; ?>_csv/<?php echo $avaliacao; ?>/formularios.php?projeto=<?php echo $dados["projeto_id"]; ?>">Formulários</a></li>
				    <li style="width:185px;">Resultados (<a href="<?php echo $caminho; ?>_csv/<?php echo $avaliacao; ?>/resultados.php?projeto=<?php echo $dados["projeto_id"]; ?>&lingua=port" style="margin: 0px; padding: 0px">português</a> | <a href="<?php echo $caminho; ?>_csv/<?php echo $avaliacao; ?>/resultados.php?projeto=<?php echo $dados["projeto_id"]; ?>&lingua=eng" style="margin: 0px; padding: 0px">inglês</a>)</li>
				    <li style="width:50px;"><a href="<?php echo $caminho; ?>_csv/<?php echo $avaliacao; ?>/resultados.php?projeto=<?php echo $dados["projeto_id"]; ?>&lingua=eng&dados_teste=1">Teste</a> </li>
				</ul>
				<?php
				    }
				?>
			</div><br><br><br><br>

			
		</article>

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>			

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 

?>