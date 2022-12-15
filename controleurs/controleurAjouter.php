<?php 
if(isset($_POST['boutonValider'])) { // formulaire soumis
	// récupération des variables
	$Nom = $_POST['TitreV'];
	$Genre = $_POST['Genre'];
	$Groupes = $_POST['GroupesV'];
	$Date = $_POST['DateCreation'];
	$Duree = $_POST['DureeV'];
	$NomFichier = $_POST['NomFichier'];
	$n = rand(1, 20);

	$insertion = insertChansons($connexion, $Groupes, $Nom, $Genre, $Date); //insertion dans la table chansons
	$id = getIDwhere($connexion, $Nom);
	$insertion = insertVersions($connexion, $id, $Duree, $Date, $n, $NomFichier); //insertion dans la table versions
}

?>