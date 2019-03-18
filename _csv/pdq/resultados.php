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

		$consulta = "SELECT * FROM tb_resultados WHERE projeto_id = {$_GET["projeto"]} AND {$_GET["formulario"]}";
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
		

		$nomes_colunas = array_merge(array('Usuario', 'Amostra', 'Codigo', 'Sessao'), $atributos);
		$colunas = "";
		foreach ($atributos as $atributo) {
			$colunas = $colunas . ", SUM(CASE WHEN r.atributo_completo_{$_GET["lingua"]} = '{$atributo}' THEN nota END)";
		}

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

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
		FROM tb_resultados AS r 
        LEFT JOIN usuarios AS u
        ON u.user_id = r.user_id
        LEFT JOIN tb_amostras AS a
		ON (a.amostra_codigo = r.amostra_codigo AND a.projeto_id = r.projeto_id)
		WHERE r.projeto_id = {$_GET["projeto"]} AND r.formulario_id = {$_GET["formulario"]} AND r.teste = {$teste}
		GROUP BY r.user_id, u.iniciais, r.sessao, r.amostra_codigo, a.amostra_descricao";
		$acesso = mysqli_query($conecta, $consulta);
		
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {
			fputcsv($output, $row);
		}
}
?>