<?php
if (isset($_POST['boutonValider'])) { // formulaire soumis

	//initialisation des variables à récupérer dans le formulaire
	$nomGenre = $_POST['Genre']; 
	$Stat = $_POST['Stats'];
	$duree_donnee = $_POST['DureeP']; 
	$NomP = $_POST['TitreP'];
	$Date = DActu($connexion)[0]['curdate']; //recuperation de la date de l'envoi du formulaire
	$dureegenre = getDuree($connexion, $nomGenre); //récupération de la durée totale disponible pour le genre

	//verification qu'il y a assez de chansons disponibles.
	if ($nomGenre != 'Aucun en particulier' && $dureegenre < ($duree_donnee - 1)) { 
		echo "<br> <strong> Erreur : Il n'y a pas assez de chansons de ce genre pour créer une playlist de cette longueur.<br> </strong>
		Il n'y a que ", round($dureegenre, 2), " minutes de $nomGenre dans la base de données.";
		exit;  //fin du script
	}

	//verification que la playlist a un nom
	if ($NomP == null) {
		$NomP = $nomGenre . $Date;  //création d'un titre automatique
	}

	//verification qu'il n'y a pas déjà une playlist avec ce nom
	$verifpl = verificationPL($connexion, $NomP);
	 if ( $verifpl>0) {
		echo "<br> <strong> Il y a déjà une playlist de ce nom. Veuillez le modifier pour continuer. </strong> <br>";
		exit;
	}

	$playlist = insertPlaylist($connexion, "$NomP", $Date); //création de la playlist
	$idP = getIdPlaylist($connexion, $NomP)[0]['IdP']; // recuperation de l'id de la playlist

	//initialisation des variables utilisées pour les vérifications
	$else = 0;
	$dureePl = 0;
	$nbchanson = 0;
	$PlSongs = array();
	$nbchansonslimit = round(($duree_donnee / 3 + 2), 0);

	if ($Stat == 'Aucune en particulier' && $nomGenre == 'Aucun en particulier') { //si on ne choisit pas de préférence boucle 1
		while ($dureePl < $duree_donnee - 1) { // tant que la playlist n'est pas assez remplie 
			$song = getSong($connexion);  //récupérer une chanson
			$idC = $song[0]['IdC'];  //récupérer son id
			$NumV = $song[0]['NumeroV']; //récupérer sa version
			$verif = isset($PlSongs[$idC]);  // vérification si la chanson a déjà été ajoutée
			if ($verif != 1) { //si pas ajoutée
				$PlSongs[$idC] = $idC;
				$nbchanson += 1; // compteur du nbre de chanson
				$dureePl += $song[0]['DureeV']; //compteur de la durée de la playlist
				$chanson = insertInclure($connexion, $idC, $NumV, $idP); //insertion dans la playlist 
			} else { // si chanson déjà ajoutée
				$else += 1;
				if ($else > 15) { // permet d'éviter les boucles trop longues
					echo "<strong>Erreur. </strong>";
					deletePlaylist($connexion, $idP); //on supprime la playlist
					exit;  //fin du script
				}
			}
			if ($dureePl > ($duree_donnee + 1)) { // si durée trop longue
				$else = 0;
				$nbchanson = 0;
				$dureePl = 0;
				$PlSongs = array();
				deleteInclure($connexion, $idP); //on vide la playlist
				continue; // on réessaye
			}
		}
	}

	elseif ($Stat == 'Aucune en particulier' && $nomGenre != 'Aucun en particulier') { //si on choisit seulement le genre boucle 2
		while ($dureePl < $duree_donnee - 1) { // tant que la playlist n'est pas assez remplie
			$song = getSongsPlaylistGenre($connexion, $nomGenre); //recuperation des chansons correspondant au genre
			$idC = $song[0]['IdC'];
			$NumV = $song[0]['NumeroV'];
			$verif = isset($PlSongs[$idC]);  // vérification si la chanson a déjà été ajoutée

			if ($verif != 1) {
				$PlSongs[$idC] = $idC;
				$nbchanson += 1;
				$dureePl += $song[0]['DureeV'];
				$chanson = insertInclure($connexion, $idC, $NumV, $idP); //insertion dans la playlist
			} else {
				$else += 1;
				if ($else > 15) {
					echo "<strong>Erreur. </strong>";
					deletePlaylist($connexion, $idP); //on supprime la playlist
					exit;  //fin du script
				}
			}
			if ($dureePl > ($duree_donnee + 1)) { // si durée trop longue
				$else = 0;
				$nbchanson = 0;
				$dureePl = 0;
				$PlSongs = array();
				deleteInclure($connexion, $idP); //on vide la playlist
				continue; // on réessaye
			}
		}
	}
	

	elseif ($Stat != 'Aucune en particulier' && $nomGenre == 'Aucun en particulier') { //si on choisit seulement la stat boucle 3
		if ($Stat = 'LastPlayed') { // si on choisit la stat LastPlayed
			while ($dureePl < $duree_donnee - 1) { // tant que la playlist n'est pas assez remplie
				$song = getStat($connexion, $nbchansonslimit, $Stat, 'ASC'); // on récupère une chanson adaptée
				$idC = $song[0]['IdC'];
				$NumV = $song[0]['NumeroV'];
				$verif = isset($PlSongs[$idC]);  // vérification si la chanson a déjà été ajoutée
				if ($verif != 1) {
					$PlSongs[$idC] = $idC;
					$nbchanson += 1;
					$dureePl += $song[0]['DureeV'];
					$chanson = insertInclure($connexion, $idC, $NumV, $idP); //insertion dans la playlist
				} else {
					$else += 1;
					if ($else > 15) {
						echo "<strong>Erreur.  </strong>";
						deletePlaylist($connexion, $idP); //on supprime la playlist
						exit;  //fin du script
					}
				}
				if ($dureePl > ($duree_donnee + 1)) { // si durée trop longue
					$else = 0;
					$nbchanson = 0;
					$dureePl = 0;
					$PlSongs = array();
					deleteInclure($connexion, $idP); //on vide la playlist
					continue; // on réessaye
				}
			}
		} else { //si on choisit la stat skipcount ou playcount
			while ($dureePl < $duree_donnee - 1) { // tant que la playlist n'est pas assez remplie
				$song = getStat($connexion, $nbchansonslimit, $Stat, 'DESC'); // on récupère une chanson adaptée
				$idC = $song[0]['IdC'];
				$NumV = $song[0]['NumeroV'];
				$verif = isset($PlSongs[$idC]);  // vérification si la chanson a déjà été ajoutée
				if ($verif != 1) {
					$PlSongs[$idC] = $idC;
					$nbchanson += 1;
					$dureePl += $song[0]['DureeV'];
					$chanson = insertInclure($connexion, $idC, $NumV, $idP); //insertion dans la playlist
				} else {
					$else += 1;
					if ($else > 15) {
						echo "<strong>Erreur. </strong>";
						deletePlaylist($connexion, $idP); //on supprime la playlist
						exit;  //fin du script
					}
				}
				if ($dureePl > ($duree_donnee + 1)) { // si durée trop longue
					$else = 0;
					$nbchanson = 0;
					$dureePl = 0;
					$PlSongs = array();
					deleteInclure($connexion, $idP); //on vide la playlist
					continue; // on réessaye
				}
			}
		}
	}
	elseif ($Stat != 'Aucune en particulier' && $nomGenre != 'Aucun en particulier') { //si on choisit les deux boucle 4
		if ($Stat = 'LastPlayed') { //si on choisit la stat LastPlayed
			while ($dureePl < $duree_donnee - 1) { // tant que la playlist n'est pas assez remplie
				$song = getStatGenre($connexion, $nbchansonslimit, $Stat, 'ASC', $nomGenre); // on récupère une chanson adaptée
				$idC = $song[0]['IdC'];
				$NumV = $song[0]['NumeroV'];
				$verif = isset($PlSongs[$idC]);  // vérification si la chanson a déjà été ajoutée
				if ($verif != 1) {
					$PlSongs[$idC] = $idC;
					$nbchanson += 1;
					$dureePl += $song[0]['DureeV'];
					$chanson = insertInclure($connexion, $idC, $NumV, $idP); //insertion dans la playlist
				} else {
					$else += 1;
					if ($else > 15) {
						echo "<strong>Erreur. </strong>";
						deletePlaylist($connexion, $idP); //on supprime la playlist
						exit;  //fin du script
					}
				}
				if ($dureePl > ($duree_donnee + 1)) { // si durée trop longue
					$else = 0;
					$nbchanson = 0;
					$dureePl = 0;
					$PlSongs = array();
					deleteInclure($connexion, $idP); //on vide la playlist
					continue; // on réessaye
				}
			}
		}
	} else { //si on choisit la stat Skipcount ou Playcount
		while ($dureePl < $duree_donnee - 1) { // tant que la playlist n'est pas assez remplie
			$song = getStatGenre($connexion, $nbchansonslimit, $Stat, 'DESC', $nomGenre); // on récupère une chanson adaptée
			$idC = $song[0]['IdC'];
			$NumV = $song[0]['NumeroV'];
			$verif = isset($PlSongs[$idC]);  // vérification si la chanson a déjà été ajoutée
			if ($verif != 1) {
				$PlSongs[$idC] = $idC;
				$nbchanson += 1;
				$dureePl += $song[0]['DureeV'];
				$chanson = insertInclure($connexion, $idC, $NumV, $idP); //insertion dans la playlist
			} else {
				$else += 1;
				if ($else > 15) {
					echo "<strong>Erreur. </strong>";
					deletePlaylist($connexion, $idP); //on supprime la playlist
					exit;  //fin du script

				}
				if ($dureePl > ($duree_donnee + 1)) { // si durée trop longue
					$else = 0;
					$nbchanson = 0;
					$dureePl = 0;
					$PlSongs = array();
					deleteInclure($connexion, $idP); //on vide la playlist
					continue; // on réessaye
				}
			}
		}
	}
	echo " <br> <strong> Vous avez créé la playlist $NomP composée de $nbchanson chansons d'une durée de $dureePl minutes. </strong>";
	echo "<br> <br> <h3> Liste des Chansons de $NomP </h3>";
	$TitreLP= getInstancesJointurePlaylist($connexion, "Playlists", "Inclure", "Chansons", "TitreC", $idP); //récupère les chansons dans la playlist
	foreach ($TitreLP as $TitreI) {
		echo "<li>";
		echo $TitreI['TitreC'];
		echo "</li>";
	}
	
}





/* 	if($verification_genre == FALSE) { // pas de série avec ce nom, insertion
		echo"Ce genre n'existe pas dans la base de données";
	}
	else {
		while($diff >= 3.5) {
			$diff=$diff-$durée_liste;
			$random=getAleatoire($connexion, 'Chansons', 'Genre');
			echo '". $version . "' ;
			$durée_liste=$durée_liste+$random[5];
			$insertion = insertVersion($connexion, $version);
			if($insertion == TRUE) {
				$message = "La version $nomVersion a bien été ajoutée !";
			}
			else {
				$message = "Erreur lors de l'insertion de la version $nomVersion.";
			}
		}
		$version=$getVersionGenreReduit($connexion, $nomVersion, $diff);
		$insertion = insertVersion($connexion, $version);
		if($insertion == TRUE) {
			$message = "La version $nomVersion a bien été ajoutée !";
		}
		else {
			$message = "Erreur lors de l'insertion de la version $nomVersion.";
		}
	}
} */

?>