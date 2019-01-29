<?php 
if (isset($_SESSION["user_id"])) {
		if ($_SESSION["funcao"] <> "Administrador") {
			header("location:Sistema/public/sessoes.php");
		}
	} else {
		header("location:Sistema/login.php");
	}
?>