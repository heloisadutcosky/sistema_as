<?php 
if (isset($_SESSION["user_id"])) {
		if ($_SESSION["funcao"] <> "Administrador") {
			header("location:{$caminho}public/sessoes.php");
		}
	} else {
		header("location:{$caminho}login.php");
	}
?>