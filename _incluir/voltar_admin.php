<?php if ($_SESSION["funcao"] == "Administrador") { ?>

<style type="text/css">
	main {
	  position: relative;
	    margin:auto;
	}
	div.voltar {
		position: absolute;
		float: bottom;
		bottom: 0;
		right: 0;
		font-size: 0.75em;
		margin-right: 15px;
		margin-bottom: 5px;
		margin-top: 20px;
	}
</style>

<div class="voltar">
	<a href="<?php echo($caminho); ?>admin/principal.php">Voltar para painel de administração</a><br><br>
</div>
<?php } ?>			