<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_admin.css">

<style type="text/css">
		li.menulateral a {
		  text-decoration: none;
		  display: block;
		  font-family: serif;
		  color: #FFF;
		  text-align: center;
		  top: 50%;
		  left: 50%;
		}

		li.submenulateral a {
		  text-decoration: none;
		  display: block;
		  color: #9A9191;
		  font-size: 90%;
		  font-family: serif;
		  font-weight: 90%;
		  text-align: center;
		}
</style>

<?php 
	if (isset($_GET["detalhe"])) {
	$_SESSION["detalhe"] = $_GET["detalhe"];
	}
?>
	
<nav>
	<ul class="menulateral">
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/principal.php?detalhe="><b>PRINCIPAL</b></a></li><br>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/usuarios/dados.php?detalhe=">USUÁRIOS</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/empresas/dados.php?detalhe=">EMPRESAS</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/produtos/dados.php">PRODUTOS</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/formularios/dados.php">FORMULÁRIOS</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/projetos/dados.php">PROJETOS</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>public/principal.php?teste=1">REVISAR SISTEMA</a></li>
		<li class="menulateral"><a href="?detalhe=exportar">EXPORTAR DADOS</a></li>
		<?php if ($_SESSION["detalhe"] == "exportar") { ?>
		<ul>
			<li class="submenulateral"><a href="<?php echo($caminho); ?>admin/exportar/dados_csv.php">Formato csv</a></li>
			<li class="submenulateral"><a href="<?php echo($caminho); ?>admin/exportar/dados_xls.php">Formato xls</a></li>
		</ul>
		<?php } ?>
	</ul>
</nav>
<br>