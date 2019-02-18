<?php 

$caminho =  "../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

if (isset($_GET["codigo"])) {

		$consulta = "SELECT * FROM formularios WHERE projeto_id = {$_GET["codigo"]}";
		$acesso = mysqli_query($conecta, $consulta);

		$nomes_colunas = array('Usuario', 'Sessao', 'Amostra');
		$colunas = "";
		while ($dados = mysqli_fetch_assoc($acesso)) {
			$nomes_colunas[] = $dados["atributo_completo"];
			$colunas = ", SUM(CASE WHEN r.atributo_completo = '{$dados["atributo_completo"]}' THEN nota END) AS {$dados["atributo_completo"]}";
		}
		
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, $nomes_colunas);

		// fetch the data
		$consulta = "SELECT u.nome, 
		r.sessao, 
		r.amostra_codigo 
		{$colunas}
		FROM resultados AS r LEFT JOIN usuarios AS u
		ON r.user_id = u.user_id
		WHERE projeto_id = {$_GET["codigo"]}
		GROUP BY u.nome, r.sessao, r.amostra_codigo";
		$acesso1 = mysqli_query($conecta, $consulta);

		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso1)) {
			fputcsv($output, $row);
		}
}
?>