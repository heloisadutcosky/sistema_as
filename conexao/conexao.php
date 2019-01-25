<?php 
	// Abrir conexão
	//$conecta = mysqli_connect("localhost", "root", "", "aboutsolution");
	$conecta = mysqli_connect("localhost", "phpmyadmin", "Lagosta1@", "about_solution");

	// Testar conexão
	if (mysqli_connect_errno() ){
		die("Conexão falhou: " . mysqli_connect_errno());
	}
?>