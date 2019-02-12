<?php 
if (isset($_SESSION["user_id"])) {
	} else {
		header("location:{$caminho}login.php");
	}
?>