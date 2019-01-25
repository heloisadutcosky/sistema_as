<?php require_once("../conexao/conexao.php"); ?>

<?php  
	$tabelas = "";
	// Iniciar sessão
	session_start();

	// Abrir consulta ao banco de dados
	if (isset($_POST["tabelas"])) {
		$tabelas = $_POST["tabelas"];

		$consulta = "SELECT * FROM " . $tabelas;
		$acesso = mysqli_query($conecta, $consulta);
	}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Dados About Solution</title>
	
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="_css/estilo_tabelas.css">

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
		<header>
			<a href="http://aboutsolution.com.br/novo/" target="_blank">
				<img src="../public/imagens/logo.jpg" width="210" height="70" title="logo About Solution">
			</a>
			<h2 class="espaco">DADOS ABOUT SOLUTION</h2>
		</header>

		<form action="alteracoes_principal.php" method="post">
			<label for="tabelas">Selecione a tabela para realizar alterações: </label>
					<select id="tabelas" name="tabelas"><br>
						<option value="usuarios">Usuários</option>
						<option value="projetos">Projetos</option>
						<option value="resultados">Resultados</option>s
					</select>
			<input type="submit" id="botao" value="Acessar"><br>
		</form>


		<?php if ($tabelas == "usuarios") { ?>
		<h4>Usuários</h4>
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
			<br>
		<?php } ?>


		<?php if ($tabelas == "projetos") { ?>
		<h4>Projetos</h4>
		<div id="janela" class="projetos">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li><?php echo utf8_encode($linha["empresa"]) ?></li>
			    <li><?php echo utf8_encode($linha["tipo_avaliacao"]) ?></li>
			    <li><?php echo utf8_encode($linha["produto"]) ?></li>
			    <li><a href="destino.php?codigo=<?php echo $linha["projeto_id"] ?>">Formulário</a> </li>
			    <li><a href="cadastrar_sessao.php?codigo=<?php echo $linha["projeto_id"] ?>">Sessão</a> </li>
			    <li><a href="alteracao.php?codigo=<?php echo $linha["projeto_id"] ?>">Alterar</a> </li>
			    <li><a href="exclusao.php?codigo=<?php echo $linha["projeto_id"] ?>">Excluir</a> </li>
			</ul>
			<?php
			    }
			?>
			</div>
			<br>
		<?php } ?>


		
		<?php include_once("../public/_incluir/rodape.php"); ?>

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
