<?php 
if (isset($_SESSION["user_id"])) {
		if ($_SESSION["funcao"] <> "Administrador") {
			header("location:{$caminho}public/principal.php");
		}
	} else {
		header("location:{$caminho}login.php");
	}
?>