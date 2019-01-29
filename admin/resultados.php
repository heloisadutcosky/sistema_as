<?php 

	$caminho =  "../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$consulta1 = "SELECT * FROM projetos";
	$acesso1 = mysqli_query($conecta, $consulta1);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Formulários</title>
	
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

	div#janela li:nth-child(1), div#cima_tabela li:nth-child(1) {
	    width:60px;
	    padding:5px 5px;
	}

	div#janela li:nth-child(2), div#cima_tabela li:nth-child(2) {
	    width:70px;  
	    padding:5px 2px;
	}    

	div#janela li:nth-child(3), div#cima_tabela li:nth-child(3) {
	    width:75px;  
	    padding:5px 2px;
	}

	div#janela li:nth-child(4), div#cima_tabela li:nth-child(4) {
	    width:230px;  
	    padding:5px 2px;
	}

	div#janela li:nth-child(5), div#cima_tabela li:nth-child(5) {
	    width:70px;  
	    padding:5px 2px;
	}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<h2 class="espaco">Resultados</h2>
		<br>

		<form action="resultados.php" method="get">
			<label for="codigo">Projeto: </label>
			<select id="codigo" name="codigo"><br>
				<?php while ($dados = mysqli_fetch_assoc($acesso1)) { ?>
					<option value="<?php echo $dados["projeto_id"]; ?>"><?php echo $dados["empresa"]; ?> - <?php echo $dados["produto"]; ?></option>
				<?php } ?>
			</select>
			<input type="submit" id="botao" value="Visualizar resultados"><br>
		</form>
		<br>

		<?php 
			if (isset($_GET["codigo"])) {
				$projeto_id = $_GET["codigo"];

				$consulta2 = "SELECT * FROM resultados WHERE projeto_id = " . $projeto_id;
				$acesso2 = mysqli_query($conecta, $consulta2); ?>

				<div id="cima_tabela" class="usuarios">
					<ul>
					    <li><b>Sessão</b></li>
					    <li><b>Usuário</b></li>
					    <li><b>Amostra</b></li>
					    <li><b>Atributo</b></li>
					    <li><b>Nota</b></li>
					</ul>
				</div>
				<div id="janela" class="usuarios">
					<?php
					    while($linha = mysqli_fetch_assoc($acesso2)) {
					?>
					<ul>
					    <li><?php echo $linha["sessao"] ?></li>
					    <li><?php echo $linha["user_id"] ?></li>
					    <li><?php echo $linha["amostra_codigo"] ?></li>
					    <li><?php echo $linha["atributo_completo"] ?></li>
					    <li><?php echo $linha["nota"] ?></li>
					</ul>
					<?php } ?>	
				</div>
		<?php } ?>	

		<div class="direita">
			<a href="principal.php">Voltar</a><br><br>
		</div>
		<br>
		<br>
		<br>		

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>