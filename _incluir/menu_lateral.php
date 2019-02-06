<link rel="stylesheet" type="text/css" href="<?php echo($caminho); ?>_css/estilo_admin.css">
<nav>
	<ul class="menulateral">
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/usuarios/dados.php">Consultar usuários</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/projetos/dados.php">Consultar projetos</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/produtos/dados.php?tipo=categorias">Consultar categorias</a></li>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/produtos/dados.php?tipo=produtos">Consultar produtos</a></li>
		<li class="menulateral">Revisar questionários</a></li>
		<ul>
			<li class="submenulateral"><a href="<?php echo($caminho); ?>public/principal.php?funcao=Consumidor">Para consumidores</a></li>
			<li class="submenulateral"><a href="<?php echo($caminho); ?>public/principal.php?funcao=Painelista">Para painelistas</a></li>
		</ul>
		<li class="menulateral"><a href="<?php echo($caminho); ?>admin/resultados.php">Visualizar resultados</a></li>
	</ul>
</nav>
<br>