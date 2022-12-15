<?php 

// recupération des versions
if(isset($_POST['boutonValider'])) {
	$NP= $_POST['NomP']; // récupère le nom de la playlist
	$id = getIdPlaylist($connexion, $NP)[0]['IdP']; // récupère l'id de la playlist du nom NomP
	$NbC = countInstancesJointure($connexion, "Playlists", "Inclure", $id); //récupère le nombre de chanson
	$TitreLP= getInstancesJointurePlaylist($connexion, "Playlists", "Inclure", "Chansons", "TitreC", $id); //récupère les chansons dans la playlist
	$DureeP = getInstancesJointurePlaylistTemps($connexion, "Inclure", "Versions", "Chansons", $id); //donne durée playlist
}


if(isset($_POST['boutonAjouter'])) {
	$NP= $_POST['NomP']; // récupère le nom de la playlist
	$Titre= $_POST['TitreC']; //récupère titre de la chanson
	echo " <strong> Vous avez rajouté la chanson $Titre dans $NP </strong>";
	$idP = getIdPlaylist($connexion, $NP)[0]['IdP']; // récupère l'id de la playlist du nom NomP
	$chanson = getChansons($connexion, $Titre); // récupère id de la version et de la chanson à partir du titre
	$idC= $chanson[0]['IdC'];
	$NumV=$chanson[0]['NumeroV'];
	$Nouvelle = insertInclure($connexion , $idC, $NumV, $idP);
}

if(isset($_POST['boutonSupprimer'])) {
	$NP= $_POST['NomP']; // récupère le nom de la playlist
	$Titre= $_POST['TitreV']; //récupère titre de la chanson
	$idP = getIdPlaylist($connexion, $NP)[0]['IdP']; // récupère l'id de la playlist du nom NomP
	$titre= getChansons($connexion, $Titre); // récupère id de la version et de la chanson à partir du titre
	$idC= $titre[0]['IdC'];
	$supp= SupprChansons($connexion, $idC, $idP);
}

if(isset($_POST['boutonComparer'])) {
	$NP1= $_POST['Nom1']; //Nom Playlist 1
	$NP2= $_POST['Nom2'];
	$id1 = getIdPlaylist($connexion, $NP1)[0]['IdP']; //Récupère Id Playlist 1
	$id2 = getIdPlaylist($connexion, $NP2)[0]['IdP']; 
	$nb1 = countInstancesInclure($connexion, $id1); // Récupère Nb de Versions de Playlist 1
	$nb2 = countInstancesInclure($connexion, $id2); 
	$nbG= $nb1 + $nb2; //Nb de Versions Total dans les 2 PLaylists
    $CompareGenre = getCompare2($connexion, 'Genre', $id1, $id2); //Fonction qui compte combien ya de chansons avec des genres en commun
    $CompareGroupe = getCompare2($connexion, 'NomG', $id1, $id2);
	$CompareTitre = getCompare($connexion, 'TitreC', $id1, $id2);
	$Genre = $CompareGenre[0]['COUNT(Genre)']; 
	$Groupe = $CompareGroupe[0]['COUNT(NomG)'] * 2; //*2 pour donner + d'importance au groupes en commun comme + rare
	$Titre = count($CompareTitre) * 3; //*3 pour donner encore + d'importance car encore + rare
	$PGenre = ($Genre * 100)/$nbG; //Calcul %
	$PGroupe = ($Groupe * 100)/$nbG;
	$PTitre = ($Titre * 100)/$nbG;
	$PG = ($PGenre+$PGroupe+$PTitre)/3; //Moyenne %
	$Score = round($PG/100,2); //Calcul score entre 0 et 1
	echo "<strong> Le score de ressemblance entre les playlists $NP1 et $NP2 est de $Score </strong>";
}

$message = "";
$NomP = getInstances($connexion, "Playlists");
if($NomP == null || count($NomP) == 0) {
	$message .= "Aucune playlist n'a été trouvée dans la base de données !";
	echo $message;
}

?>