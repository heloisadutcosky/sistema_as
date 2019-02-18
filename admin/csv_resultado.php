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

		$nomes_colunas = array('Usuario', 'Amostra', 'Codigo', 'Sessao');
		$colunas = "";
		while ($dados = mysqli_fetch_assoc($acesso)) {
			$nomes_colunas[] = $dados["atributo_completo"];
			$colunas = $colunas . ", SUM(CASE WHEN r.atributo_completo = '{$dados["atributo_completo"]}' THEN nota END) AS {$dados["atributo_completo"]}";
		}

		// output headers so that the file is downloaded rather than displayed
		//header('Content-Type: text/csv; charset=utf-8');
		//header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, $nomes_colunas);

		// fetch the data
		$consulta = "SELECT u.iniciais, 
		a.amostra_descricao,
		r.amostra_codigo,
        r.sessao
		{$colunas}
		FROM resultados AS r 
        LEFT JOIN usuarios AS u
        ON u.user_id = r.user_id
        LEFT JOIN amostras AS a
		ON (a.amostra_codigo = r.amostra_codigo AND a.projeto_id = r.projeto_id)
		WHERE r.projeto_id = {$_GET["codigo"]}
		GROUP BY r.user_id, r.sessao, r.amostra_codigo";
		$acesso = mysqli_query($conecta, $consulta);
		echo $consulta;
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {
			fputcsv($output, $row);
		}
}
?>