<?php 
	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	$projeto_id = isset($_GET["projeto"]) ? $_GET["projeto"] : 0;
	$formulario_id = isset($_GET["formulario"]) ? $_GET["formulario"] : 0;
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "cadastro";

	if (isset($_POST["atualizar"])) {

		if ($_POST["atualizar"] == "Yes") {
			
			if (isset($_POST["user_id"])) {

				$consulta = "SELECT * FROM aleatorizacao WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND user_id = {$_POST["user_id"]} AND sessao = {$_POST["sessao"]} AND ordem = {$_POST["ordem"]}";
				$acesso = mysqli_query($conecta, $consulta);
				$existe_cadastro = mysqli_fetch_assoc($acesso);

				$consulta2 = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND sessao = {$_POST["sessao"]} AND amostra_codigo = '{$_POST["amostra_codigo"]}'";
				$acesso2 = mysqli_query($conecta, $consulta2);
				$amostra = mysqli_fetch_assoc($acesso2);

				if (empty($existe_cadastro)) { 

					$cadastrar = "INSERT INTO aleatorizacao (projeto_id, formulario_id, user_id, sessao, ordem, amostra_descricao, amostra_codigo) VALUES ({$projeto_id}, {$formulario_id}, {$_POST["user_id"]}, {$_POST["sessao"]}, {$_POST["ordem"]}, '{$amostra["amostra_descricao"]}', '{$_POST["amostra_codigo"]}')";
					$operacao_cadastrar = mysqli_query($conecta, $cadastrar);
				} else {
					$alterar = "UPDATE aleatorizacao SET amostra_descricao = '{$amostra["amostra_descricao"]}', amostra_codigo = '{$_POST["amostra_codigo"]}' WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND user_id = {$_POST["user_id"]} AND sessao = {$_POST["sessao"]} AND ordem = {$_POST["ordem"]}";
					$operacao_alterar = mysqli_query($conecta, $alterar);
				}
			}

			$user_id = "";
			$sessao = "";
			$ordem = "";
			$amostra_codigo = "";
			$acao = "cadastro";

		} else {
			$user_id = $_POST["user_id"];
			$sessao = $_POST["sessao"];
			$ordem = $_POST["ordem"];
			$amostra_codigo = "";
		}
	} else {

		if ($acao == "exclusao" && isset($_GET["user"])) {

			$excluir = "DELETE FROM aleatorizacao WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND user_id = {$_GET["user"]} AND sessao = {$_GET["sessao"]} AND ordem = {$_GET["ordem"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);


			$user_id = "";
			$sessao = "";
			$ordem = "";
			$amostra_codigo = "";
			$acao = "cadastro";

		} else {

			$user_id = isset($_GET["user"]) ? $_GET["user"] : 0;
			$sessao = isset($_GET["sessao"]) ? $_GET["sessao"] : 0;
			$ordem = isset($_GET["ordem"]) ? $_GET["ordem"] : 0;

			$consulta = "SELECT * FROM aleatorizacao WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND user_id = {$user_id} AND sessao = {$sessao} AND ordem = {$ordem}";
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);

			$user_id = $dados["user_id"];
			$sessao = $dados["sessao"];
			$ordem = $dados["ordem"];
			$amostra_codigo = $dados["amostra_codigo"];
		}
	}

	$consulta = "SELECT * FROM aleatorizacao WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} ORDER BY ordem, user_id, sessao";
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
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

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
		<h2 style="margin-left: 20px">Aleatorização</h2>
		
		<div id="cima_tabela" style="width: 630px;">
			<ul>
			    <li style="width:200px;"><b>Usuário</b></li>
			    <li style="width:65px;"><b>Sessão</b></li>
			    <li style="width:65px;"><b>Ordem</b></li>
			    <li style="width:80px;"><b>Amostra</b></li>
			    <li style="width:70px;"><b>Código</b></li>
			</ul>
		</div>
		<div id="janela" style="width: 630px;">
			<?php
			    while($linha = mysqli_fetch_assoc($acesso)) {
			?>
			<ul>
			    <li style="width:200px;">
			    	<?php 
					$consulta2 = "SELECT * FROM usuarios WHERE user_id = {$linha["user_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);
					$usuario = mysqli_fetch_assoc($acesso2);
					echo utf8_encode($usuario["nome"]); ?></li>
			    <li style="width:65px; margin-left: 5px"><?php echo utf8_encode($linha["sessao"]); ?></li>
			    <li style="width:65px;"><?php echo $linha["ordem"]; ?></li>
			    <li style="width:80px;"><?php echo $linha["amostra_descricao"]; ?></li>
			    <li style="width:70px;"><?php echo $linha["amostra_codigo"]; ?></li>
			    
			    <li style="width:50px;"><a href="aleatorizacao.php?acao=alteracao&projeto=<?php echo $projeto_id; ?>&formulario=<?php echo $formulario_id; ?>&user=<?php echo $linha["user_id"]; ?>&sessao=<?php echo $linha["sessao"]; ?>&ordem=<?php echo $linha["ordem"]; ?>">Alterar</a> </li>
			    <li style="width:50px;"><a href="aleatorizacao.php?acao=exclusao&projeto=<?php echo $projeto_id; ?>&formulario=<?php echo $formulario_id; ?>&user=<?php echo $linha["user_id"]; ?>&sessao=<?php echo $linha["sessao"]; ?>&ordem=<?php echo $linha["ordem"]; ?>">Excluir</a> </li>
			</ul>
			<?php
			    }
			?>
		</div><br><br>


		<form action="aleatorizacao.php?projeto=<?php echo $projeto_id; ?>&formulario=<?php echo $formulario_id; ?>&acao=<?php echo $acao; ?>" method="post" id="myForm">
			<div style="background-color: #F8F8F8; padding: 15px 5px 15px 5px; width: 620px; margin-left: 20px">
				<div style="float: left; margin-right: 5px">
					<label for="user_id">Usuário: </label>
					<select id="user_id" name="user_id" style="width: 180px">
						<option></option>
						<?php 
						$consulta_usuarios = "SELECT * FROM usuarios";
						$acesso_usuarios = mysqli_query($conecta, $consulta_usuarios);
						while ($usuario = mysqli_fetch_assoc($acesso_usuarios)) { ?>
							<option value="<?php echo $usuario["user_id"]; ?>" <?php if($usuario["user_id"] == $user_id) { ?> selected <?php } ?>><?php echo utf8_encode($usuario["nome"]); ?></option>
						<?php } ?>
					</select>
				</div>
				<div style="float: left; margin-right: 5px">
					<label for="sessao">Sessão: </label>
					<select id="sessao" name="sessao" style="width: 45px" onchange="pegarSessao()">
						<option></option>
						<?php 
						$consulta_amostras = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id}";
						$acesso_amostras = mysqli_query($conecta, $consulta_amostras);

						$sessoes = array();
						while ($amostra = mysqli_fetch_assoc($acesso_amostras)) { 
							$sessoes[] = $amostra["sessao"];
						}
						$sessoes = array_values(array_unique(array_values($sessoes)));

						foreach ($sessoes as $i) { ?>
							<option value="<?php echo $i; ?>" <?php if($i == $sessao) { ?> selected <?php } ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>

				<script type="text/javascript">
		          function pegarSessao() {
		                var sessao = document.getElementById("sessao").value;
		                document.getElementById("atualizar").value = "No";
		                document.getElementById("myForm").submit();
		            }
		        </script>

		        <input type="hidden" id="atualizar" name="atualizar" value="Yes">

				<div style="float: left; margin-right: 5px">
					<label for="ordem">Ordem: </label>
					<input type="number" id="ordem" name="ordem" value="<?php echo($ordem); ?>" style="width: 40px;">
				</div>
				<div style="float: left; margin-right: 60px">
					<label for="amostra_codigo">Amostra: </label>
					<select id="amostra_codigo" name="amostra_codigo" style="width: 130px">
						<option></option>
						<?php 
						$consulta_amostras = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND sessao = {$sessao} ORDER BY amostra_descricao";
						$acesso_amostras = mysqli_query($conecta, $consulta_amostras);
						while ($amostra = mysqli_fetch_assoc($acesso_amostras)) { ?>
							<option value="<?php echo $amostra["amostra_codigo"]; ?>" <?php if($amostra["amostra_codigo"] == $amostra_codigo) { ?> selected <?php } ?>><?php echo utf8_encode($amostra["amostra_descricao"]) . " - " . utf8_encode($amostra["amostra_codigo"]); ?></option>
						<?php } ?>
					</select>
				</div>

				<button type="submit" style="width: 40px; margin-top: 15px; font-size: 100%; background-color: #FFF; color: #778899; text-align: center; padding: 0px;">+</button>
			</div>
		</form>


		<br><br><br><br>
		</article>

		<div class="direita">
			<a href="painel.php?acao=alteracao&codigo=<?php echo($projeto_id); ?>">Voltar</a><br><br>
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
