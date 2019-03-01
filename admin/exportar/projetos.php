<?php 

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	if (isset($_GET["apagar"])) {
		$consulta = "DELETE FROM resultados WHERE teste = 1";
		$acesso = mysqli_query($conecta, $consulta);
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
	<main style="height:450px">
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
			<h2 class="espaco">AVALIAÇÕES DE PRODUTOS</h2>
			<br>

			<form action="<?php echo $caminho; ?>_csv/resultados.php" method="get">
				<div style="float: left; margin-right: 30px">
					<label for="codigo">Projeto: </label>
					<select id="codigo" name="codigo" style="width: 330px"><br>
						<option></option>
						<?php 
						$consulta2 = "SELECT * FROM projetos";
						$acesso2 = mysqli_query($conecta, $consulta2);
						while ($dados = mysqli_fetch_assoc($acesso2)) { ?>
							<option value="<?php echo $dados["projeto_id"]; ?>"><?php echo utf8_encode($dados["nome_form"]); ?></option>
						<?php } ?>
					</select>
				</div>

				<div>
					<input type="checkbox" name="dados_teste" style="float: left; width: 20px; margin-top: 20px">
					<label for="dados_teste" style="float: left; margin-left: -5px; margin-top: 18px">Dados de teste</label><br>
				</div><br><br><br><br>

				<div class="botao" style="float: left; margin-right: 180px">
					<input id="botao" type="submit" value="Exportar resultados" style="width: 150px;"><br>
				</div>

				<div style="float: left; margin-top: 10px">
					<a href="projetos.php?apagar=1" style="background-color: #FFF; color: #778899; padding-left: 40px; padding-right: 40px;">Apagar dados de teste</a>
				</div>
				<br>

			</form>
		</article><br><br><br><br>

		<div class="direita">
			<a href="../principal.php">Voltar</a><br><br>
		</div>			

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 

?>