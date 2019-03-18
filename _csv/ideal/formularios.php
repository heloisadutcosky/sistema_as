<?php 

$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

if (isset($_GET["formulario"])) {

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		fputs($output, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
		// output the column headings
		fputcsv($output, array("projeto_id", "conjunto_atributos", "atributo", "definicao_atributo", "referencia_min", "referencia_max"));

		// fetch the data
		$consulta = "SELECT *
		FROM atributos
		WHERE formulario_id = {$_GET["formulario"]}";
		$acesso = mysqli_query($conecta, $consulta);
		
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {

			$consulta = "SELECT * from opcoes WHERE atributo_id = {$row["atributo_id"]}";
			$acesso2 = mysqli_query($conecta, $consulta);
			
			$referencias = array();
			while ($linha = mysqli_fetch_assoc($acesso2)) {
				$referencias[$linha["escala"]] = $linha["referencia"];
			}

			fputcsv($output, array($row["formulario_id"], utf8_encode($row["conjunto_atributos"]), utf8_encode($row["atributo"]), utf8_encode($row["definicao_atributo"]), utf8_encode($referencias[min(array_keys($referencias))]), utf8_encode($referencias[max(array_keys($referencias))])));
		}
}
?>