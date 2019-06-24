<header>
	<a href="http://aboutsolution.com.br/novo/" target="_blank">
	<img src="<?php echo $caminho; ?>/_imagens/sd.png" width="210" height="100"
		title="logo About Solution">
	</a>

	<div class="saudacao">
		<p>Olá, <?php echo $_SESSION["usuario"]; ?>
			<a class="logout" href="<?php echo($caminho); ?>logout.php">Logout</a>
		</p>
	</div>
</header>