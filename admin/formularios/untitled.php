	if (isset($_SESSION["amostras"]) && isset($_SESSION["n_amostra"])) {

			//echo "pagina = " . $_SESSION["pagina"] . " \n ";
			//echo "lista_amostra_associada = " . count($_SESSION["amostra_associada"]) . " \n";

			$pagina = $_SESSION["amostra_associada"][$_SESSION["pagina"]];

			if (!empty($pagina)) {

				$_SESSION["n_amostra"] = $_SESSION["n_amostra"] + 1;
				//echo "amostra = " . $_SESSION["n_amostra"] . " \n";
				//echo count($_SESSION["amostras"]);

				if ($_SESSION["n_amostra"] >= count($_SESSION["amostras"])) {
					$_SESSION["n_amostra"] = 0;
					$_SESSION["pagina"] = -1;
					$_SESSION["paginas"] = array();
					$_SESSION["sem_amostra_associada"] = array();
					$_SESSION["amostra_associada"] = array();
					$_SESSION["formularios_ids"] = array();
					$_SESSION["tipo_avaliacao"] = array();
					header("location:{$caminho}public/principal.php");
				} else {
					$_SESSION["pagina"] = 0;
					
					$_SESSION["amostra"] = $_SESSION["amostras"][$_SESSION["n_amostra"]];
					
					//header("location:{$caminho}public/amostra.php");
				}
			
			} 	

			echo $_SESSION["pagina"];

			$pagina = $_SESSION["amostra_associada"][$_SESSION["pagina"]];
			//echo $pagina;


			$_SESSION["formulario_id"] = $_SESSION["formularios_ids"][$pagina];

			//echo $_SESSION["formulario_id"];

			$tipo_avaliacao = $_SESSION["tipo_avaliacao"][$pagina];
			//echo $tipo_avaliacao;

			if ($_SESSION["pagina"]<>0) {

				$pagina = $_SESSION["sem_amostra_associada"][$_SESSION["pagina"]+1];
				
				if (!empty($tipo_avaliacao)) {
					header("location:{$caminho}public/avaliacao/{$tipo_avaliacao}.php");
				} else {
					$_SESSION["pagina"] = -1;
					header("location:{$caminho}public/principal.php");
				}
				
			}
	}
