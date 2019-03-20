<?php 

	if (isset($_GET["tabela"])) {
		$_SESSION["tabela"] = $_GET["tabela"];
		header("location:../admin/principal.php");
	} 

	if (empty($_SESSION["tabela"])) {
	 	$_SESSION["tabela"] = "about_solution";
	}	
	
	// Abrir conexão
	//$conecta = mysqli_connect("localhost", "root", "", "aboutsolution");
	$conecta = mysqli_connect("localhost", "phpmyadmin", "Lagosta1@", $_SESSION["tabela"]);

	// Testar conexão
	if (mysqli_connect_errno() ){
		die("Conexão falhou: " . mysqli_connect_errno());
	}
?>