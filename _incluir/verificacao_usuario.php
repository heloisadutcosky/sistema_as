<?php 
if (isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
	} else {
		header("location:{$caminho}login.php");
	}
?>