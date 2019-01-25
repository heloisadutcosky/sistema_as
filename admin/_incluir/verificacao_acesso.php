<?php 
if (isset($_SESSION["user_id"])) {
		if ($_SESSION["funcao"] <> "Administrador") {
			header("location:../public/sessoes.php");
		}
	} else {
		header("location:../public/login.php");
	}
?>