	<?php 

$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

if (isset($_GET["projeto"])) {

	$teste = isset($_GET["dados_teste"]) ? 1 : 0;

		$consulta = "SELECT * FROM tb_resultados WHERE projeto_id = {$_GET["projeto"]} AND formulario_id = {$_GET["formulario"]}";
		$acesso = mysqli_query($conecta, $consulta);

		$atributo_id = array();
		$atributo_completo_eng = array();
		$atributo_completo_port = array();
		while ($dados = mysqli_fetch_assoc($acesso)) {
			$atributo_id[] = $dados["atributo_id"];
			$atributo_completo_eng[] = $dados["atributo_completo_eng"];
			$atributo_completo_port[] = $dados["atributo_completo_port"];
		}
		$atributo_id = array_unique(array_values($atributo_id));
		$atributo_completo_eng = array_unique(array_values($atributo_completo_eng));
		$atributo_completo_port = array_unique(array_values($atributo_completo_port));

		if ($_GET["lingua"] == "port") {
			$atributos = $atributo_completo_port;
		} else {
			$atributos = $atributo_completo_eng;
		}
		

		$nomes_colunas = array_merge(array('Usuario'), $atributos);

		$atributo1 = $atributos[0];

		$colunas = "SELECT {$atributo1}.user_id, {$atributo1}.resposta";
		$colunas2 = "(SELECT user_id, resposta
		FROM tb_resultados
		WHERE projeto_id = {$_GET["projeto"]} AND teste = {$teste} AND atributo_completo_{$_GET["lingua"]} = '{$atributo1}'
		GROUP BY user_id) as $atributo1 ";

		$atributos = array_diff($atributos, array($atributo1));
		//print_r($atributos);

		foreach ($atributos as $atributo) {
			$colunas = $colunas . ", {$atributo}.resposta";
			$colunas2 = $colunas2 . "LEFT JOIN
		(SELECT user_id, resposta
		FROM tb_resultados
		WHERE projeto_id = {$_GET["projeto"]} AND teste = {$teste} AND atributo_completo_{$_GET["lingua"]} = '{$atributo}'
		GROUP BY user_id) as {$atributo}
		ON {$atributo1}.user_id = {$atributo}.user_id ";
		}

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, $nomes_colunas);

		// fetch the data
		$consulta = 
		"{$colunas}
		FROM
		{$colunas2}
		;";
		$acesso = mysqli_query($conecta, $consulta);

		//echo $consulta;
		
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {
			fputcsv($output, $row);
		}
}
?>