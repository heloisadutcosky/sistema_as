<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$_SESSION["sessao"] = isset($_SESSION["sessao"]) ? $_SESSION["sessao"] : 1;

	//echo {$_SESSION["formulario_id"]};

	if (isset($_POST["completo"])) {

		$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
		$acesso = mysqli_query($conecta, $consulta);
		
		$esquecido = array();
		while ($dados = mysqli_fetch_assoc($acesso)) {
				
			if (isset($_POST["atributo{$dados["atributo_id"]}"])) {

				$atributo_id = $dados["atributo_id"];
				$atributo_completo_eng = $dados["atributo_completo_eng"];
				$atributo_completo_port = $dados["atributo_completo_port"];

				//if (empty($_POST["atributo{$dados["atributo_id"]}"]) && $dados["disposicao_pergunta"] <> "ordenacao") {
				//		$esquecido[] = $dados["atributo_id"];
				//	}


				if ($dados["disposicao_pergunta"] == "checkbox" || $dados["disposicao_pergunta"] == "ordenacao") {


					$consulta2 = "SELECT * FROM opcoes WHERE atributo_id = {$dados["atributo_id"]}";
					$acesso2 = mysqli_query($conecta, $consulta2);

					while ($linha = mysqli_fetch_assoc($acesso2)) {

						if ($dados["disposicao_pergunta"] == "checkbox") {

							if (empty($_POST["atributo{$dados["atributo_id"]}"])) {
								$esquecido[] = $dados["atributo_id"];
							}

							$resposta = utf8_decode($linha["texto"]);
							//print_r($_POST["atributo{$linha["atributo_id"]}"]);
							//echo $linha["texto"];
							//echo in_array($linha["texto"], array_values($_POST["atributo{$dados["atributo_id"]}"]));
							$nota = in_array(utf8_encode($linha["texto"]), array_values($_POST["atributo{$dados["atributo_id"]}"])) ? 1 : 0;
							//echo $nota;

							if(strpos("x".strtolower($linha["texto"]), "outr")) {
								$resposta = utf8_decode($_POST["atributo{$dados["atributo_id"]}outro"]);
								$nota = 1;
							}
						}

						if ($dados["disposicao_pergunta"] == "ordenacao") {

							if (empty($_POST["atributo{$dados["atributo_id"]}_{$linha["opcao_id"]}"])) {
								$esquecido[] = $dados["atributo_id"];
							}	

							$resposta = utf8_decode($linha["texto"]);
							$nota = $_POST["atributo{$dados["atributo_id"]}_{$linha["opcao_id"]}"];
							//echo $nota;
						}
						


						$consulta_resultados_opcoes = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id} AND resposta = '{$resposta}'";
						$acesso_resultados_opcoes = mysqli_query($conecta, $consulta_resultados_opcoes);
						$resultados_opcoes = mysqli_fetch_assoc($acesso_resultados_opcoes);

						if (empty($resultados_opcoes)) {
							$inserir_opcoes = "INSERT INTO tb_resultados (projeto_id, formulario_id, sessao, user_id, amostra_codigo, atributo_id, atributo_completo_eng, atributo_completo_port, nota, teste, resposta) VALUES ({$_SESSION["projeto_id"]}, {$_SESSION["formulario_id"]}, {$_SESSION["sessao"]}, {$_SESSION["user_id"]}, '{$_SESSION["amostra"]}', {$atributo_id}, '{$atributo_completo_eng}', '{$atributo_completo_port}', {$nota}, {$_SESSION["teste"]}, '{$resposta}')";

							//echo $inserir_opcoes;

							$operacao_inserir_opcoes = mysqli_query($conecta, $inserir_opcoes);
						} else {

							$alterar_opcoes = "UPDATE tb_resultados SET nota = {$nota}, atributo_completo_eng = '{$atributo_completo_eng}', atributo_completo_port = '{$atributo_completo_port}' WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id} AND resposta = '{$resposta}'";

							//echo $alterar_opcoes;

							$operacao_alterar_opcoes = mysqli_query($conecta, $alterar_opcoes);
							//echo $alterar_opcoes;
						}
					}

					
				} else {

					if ($dados["disposicao_pergunta"] == "select") {

						if (empty($_POST["atributo{$dados["atributo_id"]}"])) {
							$esquecido[] = $dados["atributo_id"];
						}

						$nota = 0;
						$resposta = utf8_decode($_POST["atributo{$dados["atributo_id"]}"]);

						if(strpos("x".strtolower($_POST["atributo{$dados["atributo_id"]}"]), "outr")) {
							$resposta = utf8_decode($_POST["atributo{$dados["atributo_id"]}outro"]);
							$nota = 0;
						}

					} else if ($dados["disposicao_pergunta"] == "text") {
						$nota = 0;
						$resposta = utf8_decode($_POST["atributo{$dados["atributo_id"]}"]);

					} else {

						$nota = $_POST["atributo{$dados["atributo_id"]}"];
						$resposta = "";

						if ($_POST["atributo{$dados["atributo_id"]}"] == "") {
							$esquecido[] = $dados["atributo_id"];
						}
						
					}

				

				$consulta_resultados = "SELECT * FROM tb_resultados WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id};";
				$acesso_resultados = mysqli_query($conecta, $consulta_resultados);
				$resultados = mysqli_fetch_assoc($acesso_resultados);

				if (empty($resultados)) {
					$inserir = "INSERT INTO tb_resultados (projeto_id, formulario_id, sessao, user_id, amostra_codigo, atributo_id, atributo_completo_eng, atributo_completo_port, nota, teste, resposta) VALUES ({$_SESSION["projeto_id"]}, {$_SESSION["formulario_id"]}, {$_SESSION["sessao"]}, {$_SESSION["user_id"]}, '{$_SESSION["amostra"]}', {$atributo_id}, '{$atributo_completo_eng}', '{$atributo_completo_port}', {$nota}, {$_SESSION["teste"]}, '{$resposta}')";

					$operacao_inserir = mysqli_query($conecta, $inserir);
				} else {

					$alterar = "UPDATE tb_resultados SET nota = {$nota}, resposta = '{$resposta}', atributo_completo_eng = '{$atributo_completo_eng}', atributo_completo_port = '{$atributo_completo_port}' WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]} AND sessao = {$_SESSION["sessao"]} AND user_id = {$_SESSION["user_id"]} AND amostra_codigo = '{$_SESSION["amostra"]}' AND atributo_id = {$atributo_id}";

					$alterar = mysqli_query($conecta, $alterar);
					//echo $alterar;
				}
			
			} 
		} else {
			//$esquecido[] = $dados["atributo_id"];
		}
	}

		if (empty($esquecido)) {

			$amostra = $_SESSION["amostra"];

			$_SESSION["pagina"] = $_SESSION["pagina"]+1;

			$_SESSION["formulario_id"] = $_SESSION["formularios_ids"][$_SESSION["pagina"]];
			$_SESSION["amostra"] = $_SESSION["amostras"][$_SESSION["pagina"]];

			if (!empty($_SESSION["formulario_id"])) {

				if ($_SESSION["amostra"] == 0 || $_SESSION["amostra"] == $amostra) {
					header("location:{$caminho}public/avaliacao/livre.php");
				} else {
					header("location:{$caminho}public/amostra.php");
				}
			
			} else {
				session_destroy();
				header("location:{$caminho}logout.php");
				
			}
		} 
	}	

	// ##########################################################################################################################


	$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
	$acesso = mysqli_query($conecta, $consulta);
	
	$conjuntos_atributos = array();
	while ($dados = mysqli_fetch_assoc($acesso)) {
		$conjuntos_atributos[] = $dados["conjunto_atributos"];
	}

	$conjuntos_atributos = array_values(array_unique($conjuntos_atributos));
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Ideal</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo2.css">
	
	<style>
		.amostra {
		  font-size: 120%;
		  font-weight: bold;
		  color: #C2534B;
		}

		#botao {
		  text-decoration: none;
		  background-color: #FFF;
		  margin-left: 20px;
		  margin-bottom: 1px;
		  padding: 5px 15px;
		  color: #778899;;
		  border: 1px solid #696969;
		  box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
		  font-size: 100%;
		  width: 200px;
		}

		li {	
		  display: inline-block;
		  text-decoration: none;
		  background-color: #BD5555;
		  margin: 1px;
		  padding: 5px 5px;
		  padding-top: 10px;
		  color: #626161;

		  border: 1px solid #C1B7B7;
		  width: 80px;
		  height: 60px;
		  vertical-align: middle;
		  text-decoration: none;
		  text-align: center;

		  font-size: 105%;
		  color: #FFF;
		}

		li:hover {
		  background-color: #FFE1E1;
		}
	</style>

</head>
<body>
	<main>
		<?php include_once($caminho . "_incluir/topo.php"); ?>

		<article>
			<div style="margin-left: 10px">
			<h2 style="margin-bottom: 5px;"><?php echo $_SESSION["produto"]; ?></h2>

			<?php if ($_SESSION["amostra"]<>0) { ?>

				<p class="amostra"><?php echo "Amostra " . $_SESSION["amostra"]; ?></p><br>

			<?php } ?>

						<form action="" method="post" align="">

								<?php 

									foreach ($conjuntos_atributos as $conjunto_atributos) { ?>

										<div style="position: relative;">
										
										<h3 style="font-size: 120%; color: #8B0000;"><?php echo utf8_encode($conjunto_atributos); ?></h3>
										
										<?php 
										$consulta_atributos = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND conjunto_atributos = '{$conjunto_atributos}'";
										$acesso_atributos = mysqli_query($conecta, $consulta_atributos);
										$dados_atributos = mysqli_fetch_assoc($acesso_atributos);
										?>
										<p><?php echo utf8_encode($dados_atributos["descricao_conjunto"]); ?></p><br>

										<div style="margin-left: 5px">
										<?php 

										$consulta_atributos = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND conjunto_atributos = '{$conjunto_atributos}' ORDER BY ordem";
										$acesso_atributos = mysqli_query($conecta, $consulta_atributos);

										$atributos = array();
										while($dados_atributos = mysqli_fetch_assoc($acesso_atributos)) {
											$atributos[] = $dados_atributos["atributo_id"];
										}

										if (utf8_encode($conjunto_atributos) == "Afirmações") {
											shuffle($atributos);
										}

										foreach ($atributos as $atributo) {

											$consulta_atributos = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]} AND atributo_id = {$atributo}";
											$acesso_atributos = mysqli_query($conecta, $consulta_atributos);
											$dados_atributos = mysqli_fetch_assoc($acesso_atributos);
										?>



											<?php if ($dados_atributos["disposicao_pergunta"] == "text") { ?>


												<div style="background-color: #F8F8F8; padding: 10px; width: 900px; margin-left: -10px; margin-right: 10px;">

												<?php if (isset($esquecido)) {
													if (in_array($dados_atributos["atributo_id"], $esquecido)) { ?>

														<div style="background-color: #FFE1E1; padding: 1px 10px; width: 870px; margin-left: 5px;">
														<p style="color: #8B0000"><b>Ops, parece que você esqueceu de responder essa pergunta</b></p>
														</div>
													
												<?php }
												} ?>

													<br>
													<div>
														<label for="texto"><?php echo utf8_encode($dados_atributos["definicao_atributo"]); ?></label><br>
														<input type="text" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>" id="texto" style="width: 880px; height: 40px; font-size: 105%; margin-top: 5px" <?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}"])) { ?>value="<?php echo($_POST["atributo{$dados_atributos["atributo_id"]}"]) ?>"<?php } ?>>
													</div>
													<br>
												</div><br>

											<?php } ?>





											<?php if ($dados_atributos["disposicao_pergunta"] == "select") { ?>

												<br>
												<div style="background-color: #F8F8F8; padding: 10px; width: 900px; margin-left: -10px; margin-right: 10px; margin-bottom: 10px; position: relative;">

												<?php if (isset($esquecido)) {
													if (in_array($dados_atributos["atributo_id"], $esquecido)) { ?>

														<div style="background-color: #FFE1E1; padding: 1px 10px; width: 870px; margin-left: 5px;">
														<p style="color: #8B0000"><b>Ops, parece que você esqueceu de responder essa pergunta</b></p>
														</div>
													
												<?php }
												} ?>

												<?php

												$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes); ?>

												<p><?php echo utf8_encode($dados_atributos["definicao_atributo"]); ?></p>

												<div>
													<select name="atributo<?php echo $dados_atributos["atributo_id"]; ?>" style="width: 410px; font-size: 110%; background-color: #FFF;">
														<option></option>
														<?php 
														$outro = "";
														while ($dados_opcoes = mysqli_fetch_assoc($acesso_opcoes)) { ?>
															<option value="<?php echo $dados_opcoes["texto"]; ?>"
																<?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}"])) { 
																			if ($_POST["atributo{$dados_atributos["atributo_id"]}"] == $dados_opcoes["texto"]) {
																				?>
																				selected 
																			<?php } 
																		} ?>
																><?php echo utf8_encode($dados_opcoes["texto"]); ?></option>
														<?php 
															if(strpos("x".strtolower($dados_opcoes["texto"]), "outr")) {
																$outro = strtolower($dados_opcoes["texto"]);
															}
														} ?>
													</select>
												</div>
												<?php if (!empty($outro)) { ?>
													<br>
													<div>
														<label for="outro">Se <?php echo $outro; ?>, favor indicar qual(is): </label>
														<input type="text" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>outro" id="outro" style="width: 300px; font-size: 100%; margin-left: 5px" <?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}"])) { ?>value="<?php echo($_POST["atributo{$dados_atributos["atributo_id"]}outro"]) ?>"<?php } ?>>
													</div>
												<?php } ?>
												<br>

												</div>
											<?php } ?>





											<?php if ($dados_atributos["disposicao_pergunta"] == "lista") { ?>

												<br>
												<div style="background-color: #F8F8F8; padding: 10px; width: 900px; margin-left: -10px; margin-right: 10px;">

												<?php if (isset($esquecido)) {
													if (in_array($dados_atributos["atributo_id"], $esquecido)) { ?>

														<div style="background-color: #FFE1E1; padding: 1px 10px; width: 870px; margin-left: 5px;">
														<p style="color: #8B0000"><b>Ops, parece que você esqueceu de responder essa pergunta</b></p>
														</div>
													
												<?php }
												} ?>
												
												<?php
												$consulta_opcoes = "SELECT max(escala) as max_escala, min(escala) as min_escala FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes); 
												$dados_opcoes = mysqli_fetch_assoc($acesso_opcoes);
												$max_escala = $dados_opcoes["max_escala"];
												$min_escala = $dados_opcoes["min_escala"];
												if ($min_escala<=0) {
													$max_escala = $max_escala+ $min_escala+1;
												}
												
												$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes); ?>


												<p><?php echo utf8_encode($dados_atributos["definicao_atributo"]); ?></p>
												
												<?php
				
												while ($dados_opcoes = mysqli_fetch_assoc($acesso_opcoes)) {


												?>
													<li style="width: <?php echo floor(750/$max_escala); ?>px; <?php if (!empty($_POST["atributo{$dados_atributos["atributo_id"]}"])) { if ($_POST["atributo{$dados_atributos["atributo_id"]}"] == $dados_opcoes["escala"]) { ?>background-color: #FFE1E1<?php }} ?>" class="atributo<?php echo $dados_atributos["atributo_id"]; ?>" value="<?php echo $dados_opcoes["escala"]; ?>" id="<?php echo $dados_atributos["atributo_id"]; ?>-<?php echo $dados_opcoes["escala"]; ?>" onclick="armazenarValor(this.id)"><?php echo utf8_encode($dados_opcoes["texto"]); ?></li>
												<?php 

													$opcao = "";

												} ?>
													
													<br>
													<input type="hidden" id="atributo<?php echo $dados_atributos["atributo_id"]; ?>" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>" <?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}"])) { ?>value="<?php echo($_POST["atributo{$dados_atributos["atributo_id"]}"]) ?>"<?php } ?>>
													

												</div>
									
											<?php } ?>






											<?php if ($dados_atributos["disposicao_pergunta"] == "checkbox") { ?>

												<br>
												<div style="background-color: #F8F8F8; padding: 10px; width: 900px; margin-left: -10px; margin-right: 10px;">

												<?php if (isset($esquecido)) {
													if (in_array($dados_atributos["atributo_id"], $esquecido)) { ?>

														<div style="background-color: #FFE1E1; padding: 1px 10px; width: 870px; margin-left: 5px;">
														<p style="color: #8B0000"><b>Ops, parece que você esqueceu de responder essa pergunta</b></p>
														</div>
													
												<?php }
												} ?>

												<?php
												$opcoes = array();
												$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes);
													while ($dados_opcoes = mysqli_fetch_assoc($acesso_opcoes)) {
														$opcoes[] = $dados_opcoes["texto"];
													} 

												$opcoes = array_values(array_unique(array_values($opcoes)));
												shuffle($opcoes);
												?>

												<p><?php echo utf8_encode($dados_atributos["definicao_atributo"]); ?></p>

												<?php 
												$outro = "";
												foreach ($opcoes as $opcao) {
												?>
													<div style="padding: 10px">
														<label for="<?php echo $opcao; ?>" style="margin-right: 20px; float: left; font-size: 110%">
															<input type="checkbox" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>[]" id="<?php echo $opcao; ?>" value="<?php echo utf8_encode($opcao); ?>" style="transform: scale(1.2); ;width: 30px; float: left;"
															<?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}"])) { 
																	if (in_array(utf8_encode($opcao), $_POST["atributo{$dados_atributos["atributo_id"]}"])) {
																	?>
																		checked 
																	<?php } 
																} ?>
											 				/>
															<?php echo utf8_encode($opcao); ?>
														</label><br>
													</div>

													<?php 
															if(strpos("x".strtolower($opcao), "outr")) {
																$outro = strtolower($opcao);
															} ?>

														<?php } ?>

													<?php if (!empty($outro)) { ?>
														<br>
														<div>
															<label for="outro">Se <?php echo $outro; ?>, favor indicar qual(is): </label>
															<input type="text" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>outro" id="outro" style="width: 300px; font-size: 100%; margin-left: 5px" <?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}"])) { ?>value="<?php echo($_POST["atributo{$dados_atributos["atributo_id"]}outro"]) ?>"<?php } ?>>
														</div>
													<?php } ?>
													<br>
												</div>

											<?php } ?>


											<?php if ($dados_atributos["disposicao_pergunta"] == "ordenacao") { ?>


												<div style="background-color: #F8F8F8; padding: 10px; width: 900px; margin-left: -10px; margin-right: 10px;">

												<?php if (isset($esquecido)) {
													if (in_array($dados_atributos["atributo_id"], $esquecido)) { ?>

														<div style="background-color: #FFE1E1; padding: 1px 10px; width: 870px; margin-left: 5px;">
														<p style="color: #8B0000"><b>Ops, parece que você esqueceu de ordenar algum dos atributos</b></p>
														</div>
													
												<?php }
												} ?>

												<?php
												$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes); 
												$linhas = mysqli_num_rows($acesso_opcoes);

												$consulta_opcoes = "SELECT max(escala) as max_escala, min(escala) as min_escala FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes); 
												$dados_opcoes = mysqli_fetch_assoc($acesso_opcoes);
												$max_escala = $dados_opcoes["max_escala"];
												$min_escala = $dados_opcoes["min_escala"];
												if ($min_escala<=0) {
													$max_escala = $max_escala+ $min_escala+1;
												}

												$largura = floor(750/$max_escala);

												if ($largura>100) {
													$largura = 100;
												}
												
												$consulta_opcoes = "SELECT * FROM opcoes WHERE atributo_id = {$dados_atributos["atributo_id"]}";
												$acesso_opcoes = mysqli_query($conecta, $consulta_opcoes); ?>


												<p><?php echo utf8_encode($dados_atributos["definicao_atributo"]); ?></p>
												
												<?php
				
												while ($dados_opcoes = mysqli_fetch_assoc($acesso_opcoes)) {


												?>
													<div style="float: left; margin-right: 10px; position: relative;">
														<div style="width: <?php echo $largura; ?>px; background-color: #BD5555; padding: 2px 10px;">
															<p style="color: #FFF"><?php echo $dados_opcoes["texto"]; ?></p>
														</div>
														<select type="number" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>_<?php echo $dados_opcoes["opcao_id"]; ?>" id="atributo<?php echo $dados_atributos["atributo_id"]; ?>_<?php echo $dados_opcoes["opcao_id"]; ?>" style="width: 50px; margin-top: 10px; margin-left: <?php echo 5+($largura-50)/2; ?>px; position: relative; font-size: 105%;">
															<?php
															$j = 1;
															while ($j <= $linhas) {
															?>
															<option value="<?php echo $j; ?>" <?php if (isset($_POST["atributo{$dados_atributos["atributo_id"]}_{$dados_opcoes["opcao_id"]}"])) { if ($_POST["atributo{$dados_atributos["atributo_id"]}_{$dados_opcoes["opcao_id"]}"] == $j) { ?>selected <?php } } ?>><?php echo $j; ?></option>
															<?php 
																$j = $j + 1;
															} 
															?>
														</select>

														<input type="hidden" name="atributo<?php echo $dados_atributos["atributo_id"]; ?>">
													</div>
												<?php 

												} ?>

												<br><br>
												<p>.</p><br>
													
												</div><br>

											<?php } ?>

									<?php } ?>
									</div>
								<?php } ?>
									

								<script type="text/javascript">
									function armazenarValor(clickedId) {
										var nota = document.getElementById(clickedId).value;
										var atributoId = "atributo".concat(clickedId.substring(0, clickedId.indexOf("-")));
							        	document.getElementById(atributoId).value = nota;

							        	var elements = document.getElementsByClassName(atributoId);

							        	for(var i = 0; i < elements.length; i++) {
										  if (elements[i].value != nota) {
										    elements[i].style.backgroundColor = "";
										  } 
										}

										if (document.getElementById(clickedId).style.backgroundColor == "#FFE1E1") {
										  document.getElementById(clickedId).style.backgroundColor = "";
										} else {
										  document.getElementById(clickedId).style.backgroundColor = "#FFE1E1";
										}
							    	}
								</script>
							
							<br><br><br><br>
							<input type="hidden" name="amostra" value="<?php echo $_SESSION["amostra"];?>">
							<input type="submit" id="botao" value="Confirmar" name="completo" style="margin-left: 5px">
							<br>
						</form><br><br>
				
			</div>
		</article>

		<?php include_once($caminho . "_incluir/rodape.php"); ?>
		<?php include_once($caminho . "_incluir/voltar_admin.php"); ?>
		
	</main>
</body>
</html>

<?php 
	// Fechar conexão
mysqli_close($conecta);
?>