if (isset($_GET["tabela"])) {
		$_SESSION["tabela"] = $_GET["tabela"];
		header("location:../admin/principal.php");
	} else {
		$_SESSION["tabela"] = "about_solution";
	}