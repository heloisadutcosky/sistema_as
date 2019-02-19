<?php 

$caminho =  "../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

if (isset($_GET["codigo"])) {

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array("projeto_id", "conjunto_atributos", "atributo", "definicao_atributo", "referencia_min", "referencia_max"));

		// fetch the data
		$consulta = "SELECT projeto_id,
		conjunto_atributos,
		atributo,
		definicao_atributo,
		referencia_min,
		referencia_max
		FROM formularios WHERE projeto_id = {$_GET["codigo"]}";
		$acesso = mysqli_query($conecta, $consulta);
		
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {
			fputcsv($output, $row);
		}
}
?>