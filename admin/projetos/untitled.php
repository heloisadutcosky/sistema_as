<div style="float: left; margin-right: 35px;">
					<label for="tipo_avaliacao">Tipo de avaliação: </label>
					<select type="text" id="tipo_avaliacao" name="tipo_avaliacao" style="width: 250px;">
						<option></option>
						<option value="consumo" <?php if($tipo_avaliacao == "consumo") { ?> selected <?php } ?>>Consumo</option>
						<option value="selecao_painel" <?php if($tipo_avaliacao == "selecao_painel") { ?> selected <?php } ?>>Seleção de painel</option>
						<option value="pdq" <?php if($tipo_avaliacao == "pdq") { ?> selected <?php } ?>>Painel descritivo quantitativo</option>
						<option value="cata" <?php if($tipo_avaliacao == "cata") { ?> selected <?php } ?>>CATA</option>
						<option value="ideal" <?php if($tipo_avaliacao == "ideal") { ?> selected <?php } ?>>Escala do ideal</option>
						<option value="hedonica" <?php if($tipo_avaliacao == "hedonica") { ?> selected <?php } ?>>Escala hedônica</option>
						<option value="triangular" <?php if($tipo_avaliacao == "triangular") { ?> selected <?php } ?>>Teste triangular</option>
					</select>
				</div>

				<div>
					<label for="tipo_avaliador">Tipo de avaliadores: </label>
					<select id="tipo_avaliador" name="tipo_avaliador" style="width: 250px;"><br>
						<option></option>
						<option value="Consumidor" <?php if($tipo_avaliador == "Consumidor") { ?> selected <?php } ?>>Consumidor</option>
						<option value="Painelista" <?php if($tipo_avaliador == "Painelista") { ?> selected <?php } ?>>Painelista</option>
						<option value="Candidato" <?php if($tipo_avaliador == "Candidato") { ?> selected <?php } ?>>Candidato</option>
					</select>
				</div><br>

				<div>
										<label for="descricao_avaliacao" style="margin-left: 7px">Data: </label>
										<input type="date" id="descricao_avaliacao" name="descricao_avaliacao<?php echo($n); ?>" value="<?php echo $descricao_avaliacao; ?>" style="width: 80px; margin: 0px 10px 5px 0px; font-size: 75%; text-align: center;">
									</div>




<?php if ($acao == "cadastro") {

				// Verificar existência do projeto na base ------------------------------

				$consulta_projeto = "SELECT * FROM tb_projetos WHERE empresa_id = {$empresa_id} AND categoria_id = {$categoria_id} AND produto_id = {$produto_id}";

				$acesso = mysqli_query($conecta, $consulta_projeto);
				$existe_projeto = mysqli_fetch_assoc($acesso);

				if (!empty($existe_projeto)) { ?>
					<p>Esse projeto já foi cadastrado</p>
				<?php } 

				// ----------------------------------------------------------------------
					
				else {
					$cadastrar = "INSERT INTO tb_projetos (empresa_id, categoria_id, produto_id, descricao_projeto, tipo_avaliacao, tipo_avaliador, data_inicio, data_fim) VALUES ({$empresa_id}, {$categoria_id}, {$produto_id}, '{$descricao_projeto}', '{$tipo_avaliacao}', '{$tipo_avaliador}', '{$data_inicio}', '{$data_fim}')";

					$operacao_cadastrar = mysqli_query($conecta, $cadastrar);

					$consulta = "SELECT * FROM tb_projetos WHERE empresa_id = {$empresa_id} AND produto_id = {$produto_id}";
					$acesso = mysqli_query($conecta, $consulta);
					$dados_projeto = mysqli_fetch_assoc($acesso);
					$projeto_id_temp = $dados_projeto["projeto_id"];

					$n=1;
					while ($n <= $n_forms) {
						$formulario_id = $_POST["formulario_id{$n}"];
						$form_ativo = isset($_POST["form_ativo{$n}"]) ? 1 : 0;
						$tipo_avaliador = utf8_decode($_POST["tipo_avaliador{$n}"]);
						$descricao_avaliacao = utf8_decode($_POST["descricao_avaliacao{$n}"]);
						$n_sessoes = $_POST["n_sessoes{$n}"];

						$consulta_projeto = "SELECT * FROM avaliacoes WHERE projeto_id = {$projeto_id_temp} AND formulario_id = {$formulario_id}";
						$acesso = mysqli_query($conecta, $consulta_projeto);
						$existe_projeto = mysqli_fetch_assoc($acesso);

						if (!empty($existe_projeto)) { ?>
							<p>Esse formulário já foi cadastrado nesse projeto</p>
						<?php } 

						// ----------------------------------------------------------------------
							
						else {
							$cadastrar = "INSERT INTO avaliacoes (projeto_id, formulario_id, form_ativo, tipo_avaliador, descricao_avaliacao) VALUES ({$projeto_id_temp}, {$formulario_id}, {$form_ativo}, '{$tipo_avaliador}', '{$descricao_avaliacao}')";
							$operacao_cadastrar = mysqli_query($conecta, $cadastrar);
						}

						$ns=1;
						while ($ns <= $n_sessoes) {
							$sessao = $_POST["sessao{$n}_{$ns}"];
							$data = $_POST["form_ativo{$n}_{$ns}"];
							$n_amostras = $_POST["n_amostras{$n}_{$ns}"];

							$na=1;
							while ($na <= $n_amostras) {
								$amostra_codigo = $_POST["amostra_codigo{$n}_{$ns}_{$na}"];
								$amostra_descricao = $_POST["amostra_descricao{$n}_{$ns}_{$na}"];

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
			}