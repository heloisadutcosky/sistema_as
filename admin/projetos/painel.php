<?php

	$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	//Definir a ação a ser realizada
	$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";

	// Informação de projeto_id
	if (isset($_GET["codigo"])) {
		$projeto_id = $_GET["codigo"];
		$_SESSION["projeto_id"] = $projeto_id;
	} else {
		$projeto_id = 0;
	}

	// Informação de número de formulários relacionados ao projeto
	if (isset($_POST["n_mais"])) {
		$n_forms = $_POST["n_forms"]+1;
	} else if (isset($_POST["n_menos"])) {
		$n = $_POST["n_menos"];
		if (!empty($_POST["formulario_id{$n}"])) {
			$excluir = "DELETE FROM avaliacoes WHERE projeto_id = {$projeto_id} AND formulario_id = {$_POST["formulario_id{$n}"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			$excluir = "DELETE FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$_POST["formulario_id{$n}"]}";
			$operacao_excluir = mysqli_query($conecta, $excluir);

			$n_forms = $_POST["n_forms"]-1;
		}
	} else if (isset($_POST["n_forms"])) { 
		$n_forms = $_POST["n_forms"];
	} else {
		$n_forms = 1;
	}

	$consulta = "SELECT * FROM tb_projetos WHERE projeto_id = {$projeto_id}";
	$acesso = mysqli_query($conecta, $consulta);

	if (!$acesso) {
		die("Falha na consulta ao banco.");
	}

	$dados = mysqli_fetch_assoc($acesso);
	// ------------------------------------------------------------------------------

	
	// Informações preenchidas ------------------------------------------------------
	if (isset($_POST["empresa_id"])) {
		$empresa_id = $_POST["empresa_id"];
		$categoria_id = $_POST["categoria_id"];
		$produto_id = empty($_POST["produto_id"]) ? 0 : $_POST["produto_id"];
		$descricao_projeto = utf8_decode($_POST["descricao_projeto"]);
		$data_inicio = empty($_POST["data_inicio"]) ? "0000-00-00" : $_POST["data_inicio"];
		$data_fim = empty($_POST["data_fim"]) ? "0000-00-00" : $_POST["data_fim"];

		if (isset($_POST["completo"])) {
			$n_forms=$_POST["n_forms"];

			// Alterar cadastro ---------------------------------------------------------
			if ($acao == "alteracao" || $acao == "cadastro") {

				$consulta_projeto = "SELECT * FROM tb_projetos WHERE empresa_id = {$empresa_id} AND categoria_id = {$categoria_id} AND produto_id = {$produto_id}";

				$acesso = mysqli_query($conecta, $consulta_projeto);
				$existe_projeto = mysqli_fetch_assoc($acesso);

				if ($acao == "alteracao") {
					$alterar = "UPDATE tb_projetos SET empresa_id = {$empresa_id}, categoria_id = {$categoria_id}, produto_id = {$produto_id}, descricao_projeto = '{$descricao_projeto}', data_inicio = '{$data_inicio}', data_fim = '{$data_fim}' WHERE projeto_id = {$projeto_id}";
					$operacao_alterar = mysqli_query($conecta, $alterar);
				}

				if ($acao == "cadastro") {
					if (!empty($existe_projeto)) { ?>
						<p>Esse projeto já foi cadastrado</p>
					<?php } 


				// ----------------------------------------------------------------------
					
					else {
						$cadastrar = "INSERT INTO tb_projetos (empresa_id, categoria_id, produto_id, descricao_projeto, data_inicio, data_fim) VALUES ({$empresa_id}, {$categoria_id}, {$produto_id}, '{$descricao_projeto}', '{$data_inicio}', '{$data_fim}')";

						$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

						$consulta = "SELECT * FROM tb_projetos WHERE empresa_id = {$empresa_id} AND produto_id = {$produto_id}";
						$acesso = mysqli_query($conecta, $consulta);
						$dados_projeto = mysqli_fetch_assoc($acesso);
						$projeto_id = $dados_projeto["projeto_id"];
					}
				}
				
				$n=1;
				while ($n <= $n_forms) {
					$formulario_id = $_POST["formulario_id{$n}"];

					$consulta_formulario = "SELECT * FROM tb_formularios WHERE formulario_id = {$formulario_id}";
					$acesso = mysqli_query($conecta, $consulta_formulario);
					$form = mysqli_fetch_assoc($acesso);
					$tipo_avaliacao = $form["tipo_formulario"];

					$form_ativo = isset($_POST["form_ativo{$n}"]) ? 1 : 0;
					$tipo_avaliador = utf8_decode($_POST["tipo_avaliador{$n}"]);
					$descricao_avaliacao = utf8_decode($_POST["descricao_avaliacao{$n}"]);
					$n_sessoes = $_POST["n_sessoes{$n}"];

					$consulta_projeto = "SELECT * FROM avaliacoes WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id}";
						$acesso = mysqli_query($conecta, $consulta_projeto);
						$existe_projeto = mysqli_fetch_assoc($acesso);

						if (!empty($existe_projeto)) { 

							$alterar = "UPDATE avaliacoes SET form_ativo = {$form_ativo}, tipo_avaliacao = '{$tipo_avaliacao}', tipo_avaliador = '{$tipo_avaliador}', descricao_avaliacao = '{$descricao_avaliacao}' WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id}";
							$operacao_alterar = mysqli_query($conecta, $alterar);
							echo $alterar;

						} else {
							$cadastrar = "INSERT INTO avaliacoes (projeto_id, formulario_id, form_ativo, tipo_avaliacao, tipo_avaliador, descricao_avaliacao) VALUES ({$projeto_id}, {$formulario_id}, {$form_ativo}, '{$tipo_avaliacao}', '{$tipo_avaliador}', '{$descricao_avaliacao}')";
							$operacao_cadastrar = mysqli_query($conecta, $cadastrar);
						}

						$ns=1;
						while ($ns <= $n_sessoes) {
							$sessao = $_POST["sessao{$n}_{$ns}"];
							$data = empty($_POST["data{$n}_{$ns}"]) ? "0000-00-00" : $_POST["data{$n}_{$ns}"];
							$n_amostras = $_POST["n_amostras{$n}_{$ns}"];

							$na=1;
							while ($na <= $n_amostras) {
								$amostra_codigo = $_POST["amostra_codigo{$n}_{$ns}_{$na}"];
								$amostra_descricao = utf8_decode($_POST["amostra_descricao{$n}_{$ns}_{$na}"]);

								$consulta_projeto = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND sessao = {$sessao} AND amostra_codigo = '{$amostra_codigo}'";
								$acesso = mysqli_query($conecta, $consulta_projeto);
								$existe_projeto = mysqli_fetch_assoc($acesso);

								if (!empty($existe_projeto)) { 
									$alterar = "UPDATE tb_amostras SET data = '{$data}', amostra_descricao = '{$amostra_descricao}' WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND sessao = {$sessao} AND amostra_codigo = '{$amostra_codigo}'";
									$operacao_alterar = mysqli_query($conecta, $alterar);
								} else {
									$cadastrar = "INSERT INTO tb_amostras (projeto_id, formulario_id, sessao, data, amostra_codigo, amostra_descricao) VALUES ({$projeto_id}, {$formulario_id}, {$sessao}, '{$data}', '{$amostra_codigo}', '{$amostra_descricao}')";
									$operacao_cadastrar = mysqli_query($conecta, $cadastrar);
								}
								$na = $na + 1;
							}
							$ns= $ns+1;
						}
					$n= $n+1;
				}

				header("location:dados.php");
			}
			// --------------------------------------------------------------------------


			// Excluir cadastro ---------------------------------------------------------
			if ($acao == "exclusao") {
					
				$excluir = "DELETE FROM tb_projetos WHERE projeto_id = {$projeto_id}";
				$operacao_excluir = mysqli_query($conecta, $excluir);

				$excluir = "DELETE FROM avaliacoes WHERE projeto_id = {$projeto_id}";
				$operacao_excluir = mysqli_query($conecta, $excluir);

				$excluir = "DELETE FROM tb_amostras WHERE projeto_id = {$projeto_id}";
				$operacao_excluir = mysqli_query($conecta, $excluir);
				
				header("location:dados.php");
				
			}
			// --------------------------------------------------------------------------
		}
	} else {
		$empresa_id = $dados["empresa_id"];
		$categoria_id = $dados["categoria_id"];
		$produto_id = $dados["produto_id"];
		$descricao_projeto = $dados["descricao_projeto"];
		$tipo_avaliacao = $dados["tipo_avaliacao"];
		$tipo_avaliador = $dados["tipo_avaliador"];
		$data_inicio = $dados["data_inicio"];
		$data_fim = $dados["data_fim"];
	}
	// ------------------------------------------------------------------------------


	// Liberar dados da memória
	mysqli_free_result($acesso);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Alteração de usuário</title>
	
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo.css">
	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_formulario.css">

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>
		<?php include_once($caminho . "_incluir/menu_lateral.php"); ?>

		<article>
		<h2 class="espaco"><?php 
			if ($acao == "alteracao") echo "Alteração de cadastro de projeto";
			elseif ($acao == "exclusao") echo "Exclusão de projeto";
			else echo "Cadastro de projeto"; 
			?></h2>

		<br>
		<form action="painel.php?acao=<?php echo $acao; ?>&codigo=<?php echo $projeto_id; ?>&n=<?php echo $n_forms; ?>" method="post" id="myForm">

			<div style="background-color: #F8F8F8; padding: 15px 5px 15px 10px; width: 650px">
				<div>
					<label for="empresa_id">Empresa: </label>
					<select id="empresa_id" name="empresa_id" style="width: 615px;">
						<option></option>
						<?php 
						$consulta_empresas = "SELECT * FROM empresas";
						$acesso_empresas = mysqli_query($conecta, $consulta_empresas);
						while ($empresa = mysqli_fetch_assoc($acesso_empresas)) { ?>
							<option value="<?php echo $empresa["empresa_id"]; ?>" <?php if($empresa["empresa_id"] == $empresa_id) { ?> selected <?php } ?>><?php echo $empresa["nome_fantasia"]; ?></option>
						<?php } ?>
					</select>
				</div><br>

				<div style="float: left; margin-right: 35px;">
					<label for="categoria_id">Categoria: </label>
					<select id="categoria_id" name="categoria_id" style="width: 285px;" onchange="pegaCategoriaId()"><br>
						<option></option>
						<?php 
						$consulta2 = "SELECT * FROM categorias";
						$acesso2 = mysqli_query($conecta, $consulta2);
						while($linha = mysqli_fetch_assoc($acesso2)) { ?>
							<option value="<?php echo $linha["categoria_id"]; ?>" <?php if($categoria_id == $linha["categoria_id"]) { ?> selected <?php } ?>><?php echo utf8_encode($linha["categoria"]); ?></option>
						<?php } ?>
					</select>
				</div>

				<script type="text/javascript">
		          function pegaCategoriaId() {
		                var categoriaId = document.getElementById("categoria_id").value;
		                document.getElementById("atualizar").value = "No";
		                document.getElementById("myForm").submit();
		            }
		        </script>

		        <input type="hidden" id="atualizar" name="atualizar" value="Yes">

				<div>
					<label for="produto_id">Produto: </label>
					<select id="produto_id" name="produto_id" style="width: 285px;">
						<option></option>
						<?php 
						$consulta2 = "SELECT * FROM produtos WHERE categoria_id = {$categoria_id}";
						$acesso2 = mysqli_query($conecta, $consulta2);
						while($linha = mysqli_fetch_assoc($acesso2)) { ?>
							<option value="<?php echo $linha["produto_id"]; ?>" <?php if($produto_id == $linha["produto_id"]) { ?> selected <?php } ?>><?php echo utf8_encode($linha["produto"]); ?></option>
						<?php } ?>
					</select>
				</div><br>

				<div>
					<label for="descricao_projeto">Descrição do projeto: </label>
					<input type="text" id="descricao_projeto" name="descricao_projeto" value="<?php echo utf8_encode($descricao_projeto); ?>" style="width: 610px; height: 40px; text-indent: 2px">
				</div><br>

				<div style="float: left; margin-right: 10px;">
					<label for="data_inicio">Data de início: </label>
					<input type="date" id="data_inicio" name="data_inicio" value="<?php echo $data_inicio; ?>" style="width: 125px;">
				</div>

				<div>
					<label for="data_fim">Data de fim: </label>
					<input type="date" id="data_fim" name="data_fim" value="<?php echo $data_fim; ?>" style="width: 125px;">
				</div>
			</div><br><br>

			<?php 
				$consulta = "SELECT * FROM avaliacoes WHERE projeto_id = {$projeto_id}";
				$acesso = mysqli_query($conecta, $consulta);
				if (($acao == "alteracao" || $acao == "exclusao") && (!isset($_POST["empresa_id"]))) {
					$n_forms = mysqli_num_rows($acesso);
					if ($n_forms==0) {
						$n_forms=1;
					}
				}
				
				$n = 1;
				while ($n <= $n_forms) {

					$n_post = $n;
					if (isset($_POST["n_menos"])) {
						if ($n>=$_POST["n_menos"]) {
							$n_post = $n+1;
						}
					}

					// Achar número de sessões da avaliação-----------------------------------------
					if (isset($_POST["ns_mais{$n_post}"])) {
						$n_sessoes = $_POST["n_sessoes{$n_post}"]+1;
					} else if (isset($_POST["ns_menos{$n_post}"])) {
						$ns = $_POST["ns_menos{$n_post}"];
						if (!empty($_POST["sessao{$n_post}_{$ns}"])) {
							$excluir = "DELETE FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$_POST["formulario_id{$n_post}"]} AND sessao = {$_POST["sessao{$n_post}_{$ns}"]}";
							$operacao_excluir = mysqli_query($conecta, $excluir);
						}
						$n_sessoes = $_POST["n_sessoes{$n_post}"]-1;
					} else if (isset($_POST["n_sessoes{$n_post}"])) {
						$n_sessoes = $_POST["n_sessoes{$n_post}"];
					} else {
						$n_sessoes = 1;
					}
					// ------------------------------------------------------------------------------

					
					if (isset($_POST["formulario_id{$n_post}"])) {
						$dados = mysqli_fetch_assoc($acesso);
						$formulario_id = $_POST["formulario_id{$n_post}"];
						$form_ativo = isset($_POST["form_ativo{$n_post}"]) ? 1 : 0;
						$tipo_avaliador = $_POST["tipo_avaliador{$n_post}"];
						$descricao_avaliacao = $_POST["descricao_avaliacao{$n_post}"];
					} else {
						$dados = mysqli_fetch_assoc($acesso);
						$formulario_id = $dados["formulario_id"];
						$form_ativo = $dados["form_ativo"];
						$tipo_avaliador = $dados["tipo_avaliador"];
						$descricao_avaliacao = utf8_encode($dados["descricao_avaliacao"]);
					} ?>

					<div style="background-color: #F8F8F8; padding: 15px 5px 15px 10px; width: 650px; position: relative;">
						<div>
							<label for="descricao_avaliacao">Avaliação <?php echo $n; ?>: </label>
							<input type="text" id="descricao_avaliacao" name="descricao_avaliacao<?php echo($n); ?>" value="<?php echo $descricao_avaliacao; ?>" style="width: 610px;">
						</div><br>


						<div style="float: left; margin-right: 35px;">
							<label for="formulario_id">Formulário: </label>
							<select id="formulario_id" name="formulario_id<?php echo($n); ?>" style="width: 285px;">
								<option></option>
								<?php 
								$consulta2 = "SELECT * FROM tb_formularios";
								$acesso2 = mysqli_query($conecta, $consulta2);
								while($linha = mysqli_fetch_assoc($acesso2)) { ?>
									<option value="<?php echo $linha["formulario_id"]; ?>" <?php if($formulario_id == $linha["formulario_id"]) { ?> selected <?php } ?>><?php echo utf8_encode($linha["nome_formulario"]); ?></option>
								<?php } ?>
							</select>
						</div>

						<div>
							<label for="tipo_avaliador">Tipo de avaliadores: </label>
							<select id="tipo_avaliador" name="tipo_avaliador<?php echo($n); ?>" style="width: 285px;"><br>
								<option></option>
								<option value="Consumidor" <?php if($tipo_avaliador == "Consumidor") { ?> selected <?php } ?>>Consumidor</option>
								<option value="Painelista" <?php if($tipo_avaliador == "Painelista") { ?> selected <?php } ?>>Painelista</option>
								<option value="Candidato" <?php if($tipo_avaliador == "Candidato") { ?> selected <?php } ?>>Candidato</option>
							</select>
						</div><br>


						<?php 
						$formulario_id = !empty($formulario_id) ? $formulario_id : 0;

						$consulta_sessoes = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND sessao = 1";
						$acesso_sessoes = mysqli_query($conecta, $consulta_sessoes);
						$n_amostras_temp = mysqli_num_rows($acesso_sessoes);

						$consulta_sessoes = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id}";
						$acesso_sessoes = mysqli_query($conecta, $consulta_sessoes);

						if (($acao == "alteracao" || $acao == "exclusao") && (!isset($_POST["empresa_id"]))) {
							if ($n_amostras_temp <> 0) {
								$n_sessoes = round(mysqli_num_rows($acesso_sessoes)/$n_amostras_temp);
							}
							if ($n_sessoes==0 || empty($n_sessoes)) {
								$n_sessoes=1;
							}
						}

						$dados_sessoes = mysqli_fetch_assoc($acesso_sessoes);
						$sessao = 0;

						$ns = 1;
						while ($ns <= $n_sessoes) {

							$ns_post = $ns;
							if (isset($_POST["ns_menos{$n_post}"])) {
								if ($ns>=$_POST["ns_menos{$n_post}"]) {
									$ns_post = $ns+1;
								}
							}

							// Achar número de amostras da sessão-------------------------------------------
							if (isset($_POST["na_mais{$n_post}_{$ns_post}"])) {
								$n_amostras = $_POST["n_amostras{$n_post}_{$ns_post}"]+1;
							} else if (isset($_POST["na_menos{$n_post}_{$ns_post}"])) {
								$na = $_POST["na_menos{$n_post}_{$ns_post}"];
								if (!empty($_POST["amostra_codigo{$n_post}_{$ns_post}_{$na}"])) {
									$excluir = "DELETE FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$_POST["formulario_id{$n_post}"]} AND sessao = {$_POST["sessao{$n_post}_{$ns_post}"]} AND amostra_codigo = '{$_POST["amostra_codigo{$n_post}_{$ns_post}_{$na}"]}'";
									$operacao_excluir = mysqli_query($conecta, $excluir);
								}
								$n_amostras = $_POST["n_amostras{$n_post}_{$ns_post}"]-1;
							} else if (isset($_POST["n_amostras{$n_post}_{$ns_post}"])) {
								$n_amostras = $_POST["n_amostras{$n_post}_{$ns_post}"];
							} else {
								$n_amostras = 1;
							}
							
							// ------------------------------------------------------------------------------

							if (isset($_POST["sessao{$n_post}_{$ns_post}"])) {
								$sessao = $_POST["sessao{$n_post}_{$ns_post}"];
								$data = $_POST["data{$n_post}_{$ns_post}"];
							} else {
								if (!empty($sessao)) {
									while ($sessao == $dados_sessoes["sessao"]) {
										$dados_sessoes = mysqli_fetch_assoc($acesso_sessoes);
									} 
								}
								$sessao = $dados_sessoes["sessao"];
								$data = $dados_sessoes["data"];	
							} ?>
							<div style="background-color: #f9ecec; padding: 7px 5px 20px 5px; width: 610px; margin-left: 10px; position: relative;">
								<p style="margin-left: 8px; position: absolute; left: 10px; top: 5px; color: #660000"><b>Sessão e amostras: </b></p>
								<div style="position: relative; left: 300px; top: 5px">
									<div style="float: left; margin-right: 10px">
										<label for="sessao" style="margin-left: 11px; margin-bottom: 0px;">Sessão: </label>
										<input type="text" id="sessao" name="sessao<?php echo($n . "_" . $ns); ?>" value="<?php echo $sessao; ?>" style="width: 50px; margin: 0px 5px 5px 10px">
									</div>
									<div>
										<label for="data" style="margin-left: 8px; margin-bottom: 0px">Data: </label>
										<input type="date" id="data" name="data<?php echo($n . "_" . $ns); ?>" value="<?php echo $data; ?>" style="width: 120px; margin: 0px 10px 5px 5px; font-size: 75%; text-align: center;">
									</div>
								</div>

								<input type="hidden" name="n_sessoes<?php echo($n); ?>" value="<?php echo($n_sessoes); ?>">

								<div style="position: absolute; right: 50px; top: 24px">
									<button name="ns_menos<?php echo($n); ?>" type="submit" value="<?php echo($ns); ?>" style="width: 25px; font-size: 100%; background-color: #404040; color: #FFF; text-align: center; padding: 0px; padding-bottom: 1px; margin: 0px">-</button>
								</div>
								<?php if ($ns == $n_sessoes) { ?>
									<div style="position: absolute; right: 20px; top: 24px">
										<button name="ns_mais<?php echo($n); ?>" type="submit" value="<?php echo($ns); ?>" style="width: 25px; font-size: 100%; background-color: #404040; color: #FFF; text-align: center; padding: 0px; padding-bottom: 1px; margin: 0px">+</button>
									</div>
								<?php } ?>

								<?php 
								$sessao = !empty($sessao) ? $sessao : 0;

								$consulta_amostras = "SELECT * FROM tb_amostras WHERE projeto_id = {$projeto_id} AND formulario_id = {$formulario_id} AND sessao = $sessao";
								$acesso_amostras = mysqli_query($conecta, $consulta_amostras);

								if (($acao == "alteracao" || $acao == "exclusao") && (!isset($_POST["empresa_id"]))) {
									$n_amostras = mysqli_num_rows($acesso_amostras);
									if ($n_amostras==0) {
										$n_amostras=1;
									}
								} ?>

								<div style="margin-top: 15px; margin-left: 8px">
									<?php $na = 1;
									while ($na <= $n_amostras) { 

										$na_post = $na;
										if (isset($_POST["na_menos{$n_post}_{$ns_post}"])) {
											if ($na>=$_POST["na_menos{$n_post}_{$ns_post}"]) {
												$na_post = $na+1;
											}
										}

										if (isset($_POST["amostra_codigo{$n_post}_{$ns_post}_{$na_post}"])) {
											$dados_amostras = mysqli_fetch_assoc($acesso_amostras);
											$amostra_codigo = $_POST["amostra_codigo{$n_post}_{$ns_post}_{$na_post}"];
											$amostra_descricao = $_POST["amostra_descricao{$n_post}_{$ns_post}_{$na_post}"];
										} else {
											$dados_amostras = mysqli_fetch_assoc($acesso_amostras);
											$amostra_codigo = $dados_amostras["amostra_codigo"];
											$amostra_descricao = utf8_encode($dados_amostras["amostra_descricao"]);
										} ?>

										<div style="background-color: #8c8c8c; padding: 3px 5px 3px 2px; width: 180px; float: left; margin: 2px 5px 5px 5px;">
											<div style="float: left;">
												<label for="amostra_descricao" style="color: #FFF; margin-left: 5px">Amostra: </label>
												<input type="text" id="amostra_descricao" name="amostra_descricao<?php echo($n . "_" . $ns . "_" . $na); ?>" value="<?php echo $amostra_descricao; ?>" style="width: 90px; margin: 2px 2px 5px 5px">
											</div>
											<div style="float: left;">
												<label for="amostra_codigo" style="color: #FFF; margin-left: 5px">Código: </label>
												<input type="text" id="amostra_codigo" name="amostra_codigo<?php echo($n . "_" . $ns . "_" . $na); ?>" value="<?php echo $amostra_codigo; ?>" style="width: 40px; margin: 2px 5px 5px 5px">
											</div>

											<input type="hidden" name="n_amostras<?php echo($n . "_" . $ns); ?>" value="<?php echo($n_amostras); ?>">

											<button name="na_menos<?php echo($n . "_" . $ns); ?>" type="submit" value="<?php echo($na); ?>" style="width: 18px; font-size: 80%; background-color: #660000; color:#FFF; text-align: center; padding: 0px; position: relative; left: 2px; top: 2px; margin: 0px;">-</button>
											<?php if ($na == $n_amostras) { ?>
												<button name="na_mais<?php echo($n . "_" . $ns); ?>" type="submit" value="<?php echo($na); ?>" style="width: 18px; font-size: 80%; background-color: #660000; color: #FFF; text-align: center; vertical-align: middle; padding: 0px; margin: 0px;  position: relative; left: 2px; bottom: -2px">+</button>
											<?php } ?>
										</div>
									<?php 
									$na = $na + 1;
									} ?>
								</div>
								<p style="color: #faebeb">i</p><br>
							</div><br><br>
						<?php 
						$ns = $ns + 1;
						} ?>

						
						<div style="float: left; margin-right: 40px; margin-left: 5px">
							<input type="checkbox" id="form_ativo" name="form_ativo<?php echo($n); ?>" <?php if ($form_ativo == 1) { ?> 
							checked <?php } ?> style="float: left; width: 10px">
							<label for="form_ativo" style="width: 495px; margin-left: 0px">Habilitar avaliação no sistema para os respectivos usuários</label>
						</div>

						<input type="hidden" name="n_forms" value="<?php echo($n_forms); ?>">

						<div style="float: left; margin-top: -15px">
							<button name="n_menos" type="submit" value="<?php echo $n; ?>" style="width: 40px; margin-top: 10px; font-size: 120%; background-color: #FFF; color: #778899; text-align: center; padding: 0px; float: left; margin-right: 7px">-</button>
							<?php if ($n == $n_forms) { ?>
								<button name="n_mais" type="submit" value="<?php echo $n; ?>" style="width: 40px; margin-top: 10px; font-size: 120%; background-color: #FFF; color: #778899; text-align: center; padding: 0px">+</button>
							<?php } ?>
						</div><br>
						
					</div><br>
				<?php 
				$n = $n + 1;
				} ?>

			<div>
				<input name="completo" type="submit" id="botao" value="<?php 
					if ($acao == "alteracao") echo "Alterar cadastro";
					if ($acao == "exclusao") echo "Excluir cadastro";
					if ($acao == "cadastro") echo "Cadastrar";
				?>" style="margin-left: 2px"><br>
				<br>
			</div>

		</form>
		</article>

		<div class="direita">
			<a href="dados.php">Voltar</a><br><br>
		</div>
		<br>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>

	</main>
</body>
</html>

<?php 
	// Fechar conexão
	mysqli_close($conecta);
?>