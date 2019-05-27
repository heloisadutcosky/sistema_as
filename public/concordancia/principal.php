<?php 

	$caminho =  "../../";
	require_once($caminho . "conexao/conexao.php");

	// Iniciar sessão
	session_start();

	//Verificar informações de acesso
	require_once($caminho . "_incluir/verificacao_usuario.php");

	$consulta = "SELECT * FROM atributos WHERE formulario_id = {$_SESSION["formulario_id"]}";
	$acesso = mysqli_query($conecta, $consulta);
	
	$descricao_conjuntos = array();
	$atributos_id = array();
	while ($dados = mysqli_fetch_assoc($acesso)) {
		$descricao_conjuntos[] = $dados["descricao_conjunto"];
		$atributos_id[$dados["atributo_id"]] = $dados["descricao_conjunto"];
	}


	$_SESSION["descricao_conjuntos"] = array_values(array_unique($descricao_conjuntos));

	$_SESSION["atributos_id"] = array_keys($atributos_id);
	$_SESSION["atributos_id_conj"] = $atributos_id;

	shuffle($_SESSION["atributos_id"]);


	header("location:concordancia.php");

	// Fechar conexão
mysqli_close($conecta);
?>