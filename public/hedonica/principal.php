<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");
	
	// Iniciar sessão
	session_start();
	
	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$_SESSION["produto"] = isset($_GET["produto"]) ? $_GET["produto"] : $_SESSION["produto"];
	$_SESSION["correcao"] = isset($_GET["corrigir"]) ? $_GET["corrigir"] : 0;


	// Já pegar todas as informações do projeto
	if (isset($_SESSION["projeto_id"])) {

		$consulta = "SELECT * FROM avaliacoes WHERE projeto_id = {$_SESSION["projeto_id"]} AND form_ativo = 1";
		$acesso = mysqli_query($conecta, $consulta);

		$_SESSION["formularios"] = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			if (in_array($linha["tipo_avaliacao"], array("hedonica", "ideal", "cata"))) {
				$_SESSION["formularios"][$linha["tipo_avaliacao"]] = $linha["formulario_id"];
			}
		}

		print_r($_SESSION["formularios"]);

		$_SESSION["n_atributos"] = 0;
		$amostras = array();
		foreach ($_SESSION["formularios"] as $tipo_formulario => $formulario_id) {

			$consulta = "SELECT * FROM tb_amostras WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$formulario_id}";
			$acesso = mysqli_query($conecta, $consulta);
			
			while ($linha=mysqli_fetch_assoc($acesso)) {
				if ($linha["data"] == date("Y-m-d")) {
					$amostras[$linha["amostra_codigo"]] = $linha["sessao"];
				}
			}

			$consulta_atributos = "SELECT * FROM atributos WHERE formulario_id = {$formulario_id}";
			$acesso_atributos = mysqli_query($conecta, $consulta_atributos);

			$_SESSION["n_atributos"] = $_SESSION["n_atributos"] + mysqli_num_rows($acesso_atributos);
		}


		$_SESSION["sessao"] = array_values($amostras)[0];

		$_SESSION["amostras"] = array_keys($amostras);
		
		shuffle($_SESSION["amostras"]);

		$_SESSION["amostra"] = $_SESSION["amostras"][0];

		header("location:amostra.php");

	} else {
		header("location:../principal.php");
	}
?>