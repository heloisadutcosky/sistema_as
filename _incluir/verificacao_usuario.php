<?php 
if (isset($_SESSION["user_id"])) {
	} else {
		header("location:<?php echo($caminho); ?>login.php");
	}
?>