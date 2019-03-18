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

		$consulta = "SELECT * FROM tb_amostras WHERE projeto_id = {$_SESSION["projeto_id"]} AND formulario_id = {$_SESSION["formulario_id"]}";
		$acesso = mysqli_query($conecta, $consulta);

		$amostras = array();
		while ($linha=mysqli_fetch_assoc($acesso)) {
			if ($linha["data"] == date("Y-m-d")) {
				$amostras[$linha["amostra_codigo"]] = $linha["sessao"];
			}
		}

		$_SESSION["sessao"] = array_values($amostras)[0];

		$_SESSION["amostras"] = array_keys($amostras);
		
		shuffle($_SESSION["amostras"]);

		header("location:triangular.php");

	} else {
		header("location:../principal.php");
	}
?>