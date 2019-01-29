<?php 
if (isset($_SESSION["user_id"])) {
		if ($_SESSION["funcao"] <> "Administrador") {
			header("location:<?php echo($caminho); ?>public/sessoes.php");
		}
	} else {
		header("location:<?php echo($caminho); ?>login.php");
	}
?>