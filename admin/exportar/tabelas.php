<?php 

	$caminho =  "../../";
	
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
	<title>Exportar dados</title>
	
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
			<h2 class="espaco">OUTRAS INFORMAÇÕES</h2>
			<br>

			<form action="<?php echo $caminho; ?>_csv/tabelas.php" method="post">
				<div>
					<label for="tabela">Informações: </label>
					<select id="tabela" name="tabela" style="width: 330px"><br>
						<option></option>
						<option value="empresas">Empresas</option>
						<option value="usuarios">Usuários</option>
					</select>
				</div><br>

				<div>
					<input id="botao" type="submit" value="Exportar dados" style="width: 150px;"><br>
				</div>
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