<!-- header -->
<?php
require('inc/includes.php'); 
?>

<header>
	<div style="float:left;">
		<a href="index.php">
			<img src="img\Deezifyblanc.png" width="50%" height="50%">
		</a>
	</div>
	<h1><?= $nomSite ?></h1>
    <!-- le titre qui apparait dans l'onglet du navigateur :c'est une métadonnée de la page (dans la balise <head> de index.php). -->	
	<strong><?= $baseline ?></strong>
	Quelques statistiques...
	<table>
		<tr>
			<th>Top des genres du moment </th>
		</tr>
		<tr>
			<td> genre1</td>
		</tr>
</header>