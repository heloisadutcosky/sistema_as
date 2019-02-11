<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Questionário de consumo</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo $caminho; ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

	<style>
		.folha_cadastro {
		  margin-bottom: 1px;
		  padding: 5px 15px;
		  z-index: -1;
		}

	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<h2 class="espaco">TESTE DE IDENTIFICAÇÃO DE GOSTOS / SENSAÇÕES / ODORES</h2>
		<br>

		<article style="margin-left: 5px">
		<p>ETAPA 1: Você está recebendo 7 soluções identificadas com os gostos <b>doce</b>, <b>salgado</b>, <b>amargo</b>, <b>ácido</b> (ou azedo), <b>umami</b> (glutato monossódico) e sensações de <b>metálico</b> e <b>adstringente</b>. Prove-as cuidadosamente apenas com o objetivo de se familiarizar com os gostos e sensações puros.</p><br>

		<p>ETAPA 2: Você está recebendo sete soluções codificadas. Prove cuidadosamente cada solução e identifique o gosto ou a sensação percebida. Preencha, ao lado de cada código, o nome da sensação ou do gosto. Faça uma pausa e enxague a boca com água entre as provas de uma amostra para outra.</p><br>

		
		<form action="identificacao.php" method="post">

			<!-- CADASTRO -->
			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">485</b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">397</b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">152</b></p>
			<div>
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div><br>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">904</b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">633</b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">760</b></p>
			<div>
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div><br>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">291</b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div><br><br><br><br>



			
			<p>ETAPA 3: Aspire a primeira amostra. Identifique o odor e registre. Aguarde alguns segundos para aspirar a próxima ou realize o branco aspirando o braço ou a mão inodoros. Proceda igualmente para as demais amostras. Faça aspirações curtas e sequenciais e evite inalações profundas e longas.</p><br>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">A) </b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">B) </b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">C) </b></p>
			<div>
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div><br>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">D) </b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">E) </b></p>
			<div style="float: left; margin-right: 30px">
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div>

			<p style="float: left; width: 35px"><b style="font-size: 110%; color: #C2534B;">F) </b></p>
			<div>
				<label for="gosto">Gosto ou sensação</label>
				<input type="teste" name="gosto" style="width: 225px">
			</div><br><br>

			<input type="submit" id="botao" value="Enviar respostas"><br>
		</form>
		<br>
		</article>


		<br>
		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>
