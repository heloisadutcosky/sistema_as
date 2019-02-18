<?php 

	$caminho =  "../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");
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
			<h2 class="espaco">Resultados</h2>
			<br>

			<form action="csv_resultado.php" method="get">
				<div>
					<label for="codigo">Projeto: </label>
					<select id="codigo" name="codigo"><br>
						<?php 
						$consulta2 = "SELECT * FROM projetos";
						$acesso2 = mysqli_query($conecta, $consulta2);
						while ($dados = mysqli_fetch_assoc($acesso2)) { ?>
							<option value="<?php echo $dados["projeto_id"]; ?>"><?php echo $dados["empresa"]; ?> - <?php echo $dados["produto"]; ?></option>
						<?php } ?>
					</select>
				</div><br>

				<div>
					<input id="botao" type="submit" value="Exportar resultados" style="width: 150px;"><br>
				</div>
			</form>
		</article><br><br><br><br>

		<div class="direita">
			<a href="principal.php">Voltar</a><br><br>
		</div>			

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 

?>