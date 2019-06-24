	<?php 

$caminho =  "../../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	if (isset($_SESSION["user_id"])) {
			if ($_SESSION["funcao"] <> "Administrador" && $_SESSION["funcao"] <> "Administrador restrito") {
				header("location:{$caminho}public/principal.php");
			}
		} else {
			header("location:{$caminho}login.php");
		}

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

if (isset($_GET["projeto"])) {

	$teste = isset($_GET["dados_teste"]) ? 1 : 0;
		

		$nomes_colunas = array('Usuario', 'Amostra', 'Codigo', 'Sessao', 'Atributo', 'Opcao', 'Resposta');
		

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, $nomes_colunas);

		// fetch the data
		$consulta = "SELECT u.cpf, 
		a.amostra_descricao,
		r.amostra_codigo,
        r.sessao,
		r.atributo_completo_{$_GET["lingua"]},
		r.resposta,
		r.nota
		FROM tb_resultados AS r 
        LEFT JOIN usuarios AS u
        ON u.user_id = r.user_id
        LEFT JOIN tb_amostras AS a
		ON (a.amostra_codigo = r.amostra_codigo AND a.projeto_id = r.projeto_id)
		WHERE r.projeto_id = {$_GET["projeto"]} AND r.teste = {$teste}
		GROUP BY r.user_id, u.cpf, r.sessao, r.amostra_codigo, a.amostra_descricao, r.atributo_completo_{$_GET["lingua"]}, r.resposta, r.nota";
		$acesso = mysqli_query($conecta, $consulta);

		//echo $consulta;
		
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {
			fputcsv($output, $row);
		}
}
?>