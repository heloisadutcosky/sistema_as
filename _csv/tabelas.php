<?php 

$caminho =  "../";
	
	// Iniciar sessão
	session_start();

	//Verificar permissão de acesso (só para administradores)
	require_once($caminho . "_incluir/verificacao_acesso.php");

	//Estabelecer conexão a base de dados
	require_once($caminho . "conexao/conexao.php");

	if (isset($_GET["tabela"])) {

		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		if ($_GET["tabela"] == "empresas") {
			$head = array("ID Empresa", "Nome fantasia", "Relacao", "Razao social", "CNPJ", "CEP", "Logradouro", "Numero", "Complemento", "Bairro", "Cidade", "Estado", "Inscricao Estadual");
		} 

		if ($_GET["tabela"] == "usuarios") {
			$head = array("ID Usuario", "Funcao", "CPF", "Nome", "Iniciais", "Sexo", "Data de nascimento", "Escolaridade", "Email", "Telefone", "", "RG", "Orgao Emissor", "CEP", "Logradouro", "Numero", "Complemento", "Bairro", "Cidade", "Estado", "Intolerancias", "Fumante");
		}
		
		fputcsv($output, $head);

		// fetch the data
		$consulta = "SELECT * FROM {$_GET["tabela"]}";
		$acesso = mysqli_query($conecta, $consulta);
		
		// loop over the rows, outputting them
		while ($row = mysqli_fetch_assoc($acesso)) {
			fputcsv($output, $row);
		}
}
?>