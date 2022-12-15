<?php

/*
** Il est possible d'automatiser le routing, notamment en cherchant directement le fichier controleur et le fichier vue.
** ex, pour page=afficher : verification de l'existence des fichiers controleurs/controleurAfficher.php et vues/vueAfficher.php
** Cela impose un nommage strict des fichiers.
*/

$routes = array(
	'afficher' => array('controleur' => 'controleurAfficher', 'vue' => 'vueAfficher'),
	'ajouter' => array('controleur' => 'controleurAjouter', 'vue' => 'vueAjouter'),
	'rechercher' => array('controleur' => 'controleurRechercher', 'vue' => 'vueRechercher'),
	'aleatoire' => array('controleur' => 'controleurAleatoire', 'vue' => 'vueAleatoire'),
	'gerer' => array('controleur' => 'controleurGerer', 'vue' => 'vueGerer'),
	'importer' => array('controleur' => 'controleurImporter', 'vue' => 'vueImporter'),
);

?>
