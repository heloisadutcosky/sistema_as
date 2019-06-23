<?php 

	$caminho =  "../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$funcao_temp = isset($_GET["funcao"]) ? $_GET["funcao"] : $_SESSION["funcao"];
	
	if (!isset($_SESSION["teste"])) {
		$_SESSION["teste"] = isset($_GET["teste"]) ? $_GET["teste"] : 0;
	}

	$corrigir = isset($_GET["corrigir"]) ? $_GET["corrigir"] : 0;


if (isset($_GET["codigo"])) {

	$_SESSION["projeto_id"] = $_GET["codigo"];

		$_SESSION["pagina"] = 0;
		$_SESSION["n_amostra"] = 0;

		// Setar projeto e categoria

			$consulta = "SELECT * FROM tb_projetos WHERE projeto_id = {$_SESSION["projeto_id"]}"; 
			$acesso = mysqli_query($conecta, $consulta);
			$dados = mysqli_fetch_assoc($acesso);

			$_SESSION["produto_id"] = $dados["produto_id"];
			$_SESSION["categoria_id"] = $dados["categoria_id"];

			$consulta2 = "SELECT * FROM categorias WHERE categoria_id = {$_SESSION["categoria_id"]}"; 
			$acesso2 = mysqli_query($conecta, $consulta2);
			$dados2 = mysqli_fetch_assoc($acesso2);
			$_SESSION["categoria"] = $dados2["categoria"];

			$consulta2 = "SELECT * FROM produtos WHERE produto_id = {$_SESSION["produto_id"]}"; 
			$acesso2 = mysqli_query($conecta, $consulta2);
			$dados2 = mysqli_fetch_assoc($acesso2);
			$_SESSION["produto"] = $dados2["produto"];


			// Achar avaliacoes dentro do projeto
			$consulta = "SELECT * FROM avaliacoes WHERE form_ativo = 1 AND projeto_id = {$_SESSION["projeto_id"]} ORDER BY pagina, amostra_associada";
			$acesso = mysqli_query($conecta, $consulta);
			$rows = mysqli_num_rows($acesso);

			$formularios_ids = array();
			$amostra_associada = array();
			while($linha = mysqli_fetch_assoc($acesso)) { 
				$formularios_ids[] = $linha["formulario_id"];
				$amostra_associada[] = $linha["amostra_associada"];
						//echo "{$caminho}public/{$_SESSION["tipo_avaliacao"]}/principal.php" . "<br>";
			}
			

			if (in_array("pdq", $_SESSION["tipo_avaliacao"])) {
				$paginas = array_keys($_SESSION["tipo_avaliacao"], "pdq");
				$_SESSION["formulario_id"] = $_SESSION["formularios_ids"][$paginas[0]];
				header("location:{$caminho}public/pdq/principal.php");
			}

				//print_r($_SESSION["formularios_ids"]);
				//print_r($_SESSION["tipo_avaliacao"]);
				//print_r($_SESSION["amostra_associada"]);
				//print_r($_SESSION["sem_amostra_associada"]);


			// Achar amostras dentro do projeto
			$consulta = "SELECT * FROM avaliacoes WHERE projeto_id = {$_SESSION["projeto_id"]}";
			$acesso = mysqli_query($conecta, $consulta);
			$linha=mysqli_fetch_assoc($acesso);
			$aleatorizacao_manual = $linha["aleatorizacao_manual"];

			if ($aleatorizacao_manual == 0) {
			
				$consulta = "SELECT * FROM tb_amostras WHERE projeto_id = {$_SESSION["projeto_id"]}";
				$acesso = mysqli_query($conecta, $consulta);

			} else {

				$consulta = "SELECT * FROM aleatorizacao WHERE projeto_id = {$_SESSION["projeto_id"]} AND user_id = {$_SESSION["user_id"]}";
				$acesso = mysqli_query($conecta, $consulta);
			}

			$amostras = array();
			while ($linha=mysqli_fetch_assoc($acesso)) {
				//echo $linha["data"];
				//echo date("Y-m-d");
				if (date("Y-m-d") == $linha["data"]) { 
					//echo $linha["data"];
					$amostras[] = $linha["amostra_codigo"];
					$_SESSION["sessao"] = $linha["sessao"];
				}
			}

			//print_r($_SESSION["amostras"]);

			//Definir ordem de apresentação
			print_r($amostra_associada);
			print_r($formularios_ids);

			$_SESSION["formularios_ids"] = array();
			$_SESSION["amostras"] = array();

			$pagina = 0;
			while ($pagina < count($_SESSION["paginas"])) {
					if ($amostra_associada[$pagina] == 0) {
						$_SESSION["formularios_ids"][] = $formularios_ids[$pagina];
						$_SESSION["amostras"][] = 0;
					} 
					$pagina = $pagina +1;
				}

			shuffle($amostras);

			foreach ($amostras as $amostra) {
				$pagina = 0;
				while ($pagina < count($_SESSION["paginas"])) {
					if ($amostra_associada[$pagina] == 1) {
						$_SESSION["formularios_ids"][] = $formularios_ids[$pagina];
						$_SESSION["amostras"][] = $amostra;
					}
					$pagina = $pagina +1;
				}
			}

			print_r($_SESSION["amostras"]);
			print_r($_SESSION["formularios_ids"]);
	
		//echo $_SESSION["pagina"];
	
		$_SESSION["formulario_id"] = $_SESSION["formularios_ids"][$_SESSION["pagina"]];
		$_SESSION["amostra"] = $_SESSION["amostras"][$_SESSION["pagina"]];
		

		if ($_SESSION["amostra"] == 0) {
			header("location:{$caminho}public/avaliacao/livre.php");
		} else {
			header("location:{$caminho}public/amostra.php");
		}
	}
	 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Sessões</title>
	<link rel="shortcut icon" href="/favicon.png" type="image/png">
	<link rel="shortcut icon" type="image/png" href="{$caminho}_incluir/trigo.png" />
	<meta charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">

	<style type="text/css">
		
		li.menu3 a {
		  text-decoration: none;
		  background-color: #F8F8F8;
		  margin-bottom: 1px;
		  padding: 2px 12px;
		  color: #686868;
		  border: 1px solid #B8B8B8;
		}

		li.menu3 a:hover {
		  background-color: #B8B8B8;
		  margin: 0 auto;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article style="margin-left: 30px">
			
				<?php if ($corrigir==1) { ?>
					<p><u>Em qual avaliação você deseja retornar?</u></p>
				<?php } else { ?>
					<p>Muito bem vindo(a), <?php echo $_SESSION["usuario"]; ?>! 
					<u>Qual produto você avaliará hoje?</u>
					</p>
				<?php } ?>
		</article>

		<nav>
			<ul style="list-style: none;">
			<?php 

				$consulta = "SELECT * FROM tb_projetos";
				$acesso = mysqli_query($conecta, $consulta);
				$rows = mysqli_num_rows($acesso);

				//if ($rows == 1) {
				//	$dados = mysqli_fetch_assoc($acesso);
				//	$_SESSION["form"] = $dados["nome_form"];
					//header("location:principal.php?codigo={$dados["projeto_id"]}&funcao={$funcao_temp}&teste={$_SESSION["teste"]}");
				//}


				$algum=0;
				while($linha = mysqli_fetch_assoc($acesso)) { 


						if (date("Y-m-d") > $linha["data_inicio"] && date("Y-m-d") < $linha["data_fim"]) { 
							$algum = 1; ?>
						
						<img src="
						<?php 
						if ($linha["produto_id"]<>0) {
							$consulta2 = "SELECT * FROM produtos WHERE produto_id = {$linha["produto_id"]}";
							$acesso2 = mysqli_query($conecta, $consulta2);
							$dados2 = mysqli_fetch_assoc($acesso2);
							$produto = $dados2["produto"];
						} else {
							$consulta2 = "SELECT * FROM categorias WHERE categoria_id = {$linha["categoria_id"]}";
							$acesso2 = mysqli_query($conecta, $consulta2);
							$dados2 = mysqli_fetch_assoc($acesso2);
							$produto = $dados2["categoria"];
						}
						echo utf8_encode($dados2["url_imagem"]); ?>
						" width="100" height="75" style="float: left;"><br><br>
						<li class="menu"><a href="principal.php?codigo=<?php echo $linha["projeto_id"]; ?>&funcao=<?php echo $funcao_temp; ?>&teste=<?php echo $_SESSION["teste"]; ?>&corrigir=<?php echo $corrigir; ?>&pagina=0"><?php echo utf8_encode($produto); ?></a></li><br><br>
					<?php } ?>
				<?php } ?>

			<?php //if ($algum==0) { ?>
				<br><br>
				<li class="menu3"><a href="principal.php?corrigir=1&funcao=<?php echo $funcao_temp; ?>&teste=<?php echo $_SESSION["teste"]; ?>" style="font-size: 90%;">Corrigir notas</a></li>
			<?php //} ?>
			</ul>
		</nav>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
if (isset($acesso)) {
	mysqli_free_result($acesso);
}
	mysqli_close($conecta);
?>