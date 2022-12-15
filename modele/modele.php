<!-- là ou on met sql -->

<?php 

// connexion à la BD, retourne un lien de connexion
function getConnexionBD() {
	$connexion = mysqli_connect(SERVEUR, UTILISATRICE, MOTDEPASSE, BDD);
	if (mysqli_connect_errno()) {
	    printf("Échec de la connexion : %s\n", mysqli_connect_error());
	    exit();
	}
	mysqli_query($connexion,'SET NAMES UTF8'); // noms en UTF8
	return $connexion;
}

// déconnexion de la BD
function deconnectBD($connexion) {
	mysqli_close($connexion);
}


//inserer des chansons
function insertChansons($connexion, $version, $groupe, $genre, $Date) {  
	$version = mysqli_real_escape_string($connexion, $version); // au cas où $nomSerie provient d'un formulaire
	$requete = "INSERT INTO Chansons (IdC, NomG, TitreC, Genre, DateCreation) VALUES ( NULL, '$version', '$groupe', '$genre', '$Date')";
	$res = mysqli_query($connexion, $requete);
	return $res;
}

//inserer des versions
function insertVersions($connexion, $id, $Duree, $Date, $n, $NomFichier) {
	$Duree = mysqli_real_escape_string($connexion, $Duree);
	$Date = mysqli_real_escape_string($connexion, $Date);
	$n = mysqli_real_escape_string($connexion, $n);
	$NomFichier = mysqli_real_escape_string($connexion, $NomFichier);
    $requete = "INSERT INTO Versions (IdC, DureeV, DateV, NumeroV, NomFichier) VALUES ('$id', '$Duree', '$Date', '$n', '$NomFichier')";
    $res = mysqli_query($connexion, $requete);
	return $res;
}
// nombre d'instances d'une table $nomTable
function countInstances($connexion, $nomTable) {
	$requete = "SELECT COUNT(*) AS nb FROM $nomTable";
	$res = mysqli_query($connexion, $requete);
	if($res != FALSE) {
		$row = mysqli_fetch_assoc($res);
		return $row['nb'];
	}
	return -1;  // valeur négative si erreur de requête (ex, $nomTable contient une valeur qui n'est pas une table)
}

//compte les instances de la table inclure (nbre de chansons) d'une playlist particulière
function countInstancesInclure($connexion, $id) {
	$requete = "SELECT COUNT(*) AS nb FROM Inclure WHERE IdP = $id";
	$res = mysqli_query($connexion, $requete);
	if($res != FALSE) {
		$row = mysqli_fetch_assoc($res);
		return $row['nb'];
	}
	return -1;  // valeur négative si erreur de requête (ex, $nomTable contient une valeur qui n'est pas une table)
}

// importes dans les tables groupes chansons et albums
function importationGCA($connexion, $artiste, $album, $titre, $genre){
	$requete = "INSERT INTO Groupes_de_musique (NomG) VALUES ('$artiste')";
	$requete2="INSERT INTO Chansons (idC, TitreC, NomG, Genre) VALUES (NULL, '$titre', '$artiste', '$genre')" ;
	$requete3="INSERT INTO Albums (TitreA, NomG) VALUES ('$album', '$artiste') ";
	mysqli_query($connexion, $requete);
	mysqli_query($connexion, $requete2);
	mysqli_query($connexion, $requete3);
}

//importe dans les tables versions et avoir
function importationVA($connexion, $id, $n, $duree, $nomfich, $playcount, $skipcount, $lastplayed, $filesize){
	$requete = "INSERT INTO Versions (IdC, NumeroV, DureeV, NomFichier) VALUES ('$id', '$n', '$duree', '$nomfich')";
	$requete2="INSERT INTO Avoir (IdC, NumeroV, NomPropriete, ValeurPro) VALUES ('$id', '$n', 'Filesize', '$filesize' )";
	$requete3="INSERT INTO Avoir (IdC, NumeroV, NomPropriete, ValeurPro) VALUES ('$id', '$n', 'Playcount', '$playcount')";
	$requete4="INSERT INTO Avoir (IdC, NumeroV, NomPropriete, ValeurPro) VALUES ('$id', '$n', 'Skipcount', '$skipcount' )";
	$requete5="INSERT INTO Avoir (IdC, NumeroV, NomPropriete, ValeurPro) VALUES ('$id', '$n', 'LastPlayed', '$lastplayed');";
	mysqli_query($connexion, $requete);
	mysqli_query($connexion, $requete2);
	mysqli_query($connexion, $requete3);
	mysqli_query($connexion, $requete4);
	mysqli_query($connexion, $requete5);
}

//récuperer le nombre d'instances d'une jointure d'une playlist
function countInstancesJointure($connexion, $nomTable, $nomTable2, $id){
	$requete = "SELECT COUNT(*) AS nb FROM $nomTable NATURAL JOIN $nomTable2 WHERE IdP = $id";
	$res = mysqli_query($connexion, $requete);
	if($res != FALSE) {
		$row = mysqli_fetch_assoc($res);
		return $row['nb'];
	}
	return -1;
}

//récupérer un top5 pour avoir de statistiques
function getTop5($connexion, $table, $colonne){ 
	$requete = "SELECT COUNT($colonne), $colonne FROM $table GROUP BY $colonne ORDER BY COUNT($colonne) DESC LIMIT 5";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}


// retourne les instances d'une table $nomTable
function getInstances($connexion, $nomTable) {
	$requete = "SELECT * FROM $nomTable";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//récupère des instances d'une colonne précise
function getInstancesPrecisDistinct($connexion, $nomTable, $colonne) {
	$requete = "SELECT DISTINCT $colonne FROM $nomTable";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//récupère id d'une chanson
function getIDwhere($connexion, $TitreC){
	$requete = "SELECT IdC FROM Chansons WHERE TitreC = '$TitreC' ;";
	$res = mysqli_query($connexion, $requete);
	$id = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $id[0]['IdC'];
}

//recupere chansons pour une statistique choisie
function getStat($connexion, $limit, $stat, $ascdesc){
	$requete = "SELECT IdC, DureeV, NumeroV  
	FROM (SELECT *
        FROM Avoir NATURAL JOIN Versions
		WHERE NomPropriete = '$stat'
		ORDER BY ValeurPro $ascdesc
		LIMIT $limit ) AS T
	ORDER BY rand()
	LIMIT 1";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//recupere une chanson pour une statistique et un genre choisis
function getStatGenre($connexion, $limit, $stat, $ascdesc, $genre){
	$requete = "SELECT IdC, DureeV, NumeroV  
	FROM (SELECT *
	FROM Chansons NATURAL JOIN Versions NATURAL JOIN Avoir 
	Where Genre = '$genre' and NomPropriete = '$stat' 
	ORDER BY ValeurPro $ascdesc
	LIMIT $limit) AS T
	ORDER BY rand()
	LIMIT 1";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//recupere le resultat d'une jointure
function getInstancesJointure($connexion, $nomTable, $nomTable2) {
	$requete = "SELECT * FROM $nomTable NATURAL JOIN $nomTable2";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//recupere toutes les infos pour une playlist
function getInstancesJointurePlaylist($connexion, $nomTable, $nomTable2, $nomTable3 ,$colonne, $id){
	$requete = "SELECT $colonne FROM $nomTable NATURAL JOIN $nomTable2 NATURAL JOIN $nomTable3 WHERE IdP = $id";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//verifie si la playlist existe déjà
function verificationPL($connexion, $NomP){
	$requete = "SELECT COUNT(*) as nb FROM Playlists Where TitreP='$NomP'";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances[0]['nb'];
}
// récupère la durée d'une playlist
function getInstancesJointurePlaylistTemps($connexion, $nomTable, $nomTable2, $nomTable3 , $id){
	$requete = "SELECT SUM(DureeV) AS DureeP FROM $nomTable NATURAL JOIN $nomTable2 NATURAL JOIN $nomTable3 WHERE IdP = $id";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances[0]['DureeP'];
}

//recupère la duree totale des versions d'un genre
function getDuree($connexion, $genre){
	$requete = "SELECT SUM(DureeV) as DureeG FROM Versions NATURAL JOIN Chansons WHERE Genre = '$genre'";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances[0]['DureeG'];
}

//recupere l'id d'une playlist
function getIdPlaylist ($connexion, $titrep){
	$requete = "SELECT IdP FROM Playlists WHERE TitreP = '$titrep'";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//recupere les chansons
function getSongs($connexion, $song) {
	$requete = "SELECT * FROM $song";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//recupere une chanson aléatoire
function getSong($connexion) {
	$requete = "SELECT IdC, DureeV, NumeroV FROM Versions NATURAL JOIN Chansons ORDER BY rand() LIMIT 1";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//recupere une chanson aleatoire avec un genre precis
function getSongsPlaylistGenre($connexion, $genre) {
	$requete = "SELECT IdC, DureeV, NumeroV
				FROM Versions NATURAL JOIN Chansons
				WHERE Genre= '$genre'
				ORDER BY rand()
				LIMIT 1";
	$res = mysqli_query($connexion, $requete);
	return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

//inserer une playlist
function insertPlaylist($connexion, $Titre, $Date) {
    $requete = "INSERT INTO Playlists (TitreP, DateP) VALUES ('$Titre', '$Date')";
    $res = mysqli_query($connexion, $requete);
	return $res;
}

//inserer dans inclure les chansons de la playlist
function insertInclure($connexion, $idC, $Num, $idP) {
    $requete = "INSERT INTO Inclure (IdC, NumeroV, IdP) VALUES ('$idC','$Num','$idP')";
    $res = mysqli_query($connexion, $requete);
	return $res;
}

//supprimer les chansons  de la playlist
function deleteInclure($connexion, $idP) {
    $requete = "DELETE FROM Inclure  WHERE  IdP='$idP' ";
    mysqli_query($connexion, $requete);
}

//supprimer une playlist
function deletePlaylist($connexion, $idP) {
    $requete = "DELETE FROM Playlist WHERE  IdP='$idP' ";
	$requete2 = "DELETE FROM Inclure  WHERE  IdP='$idP' ";
    mysqli_query($connexion, $requete);
	mysqli_query($connexion, $requete2);
}

//récupérer la date
function DActu($connexion) {
    $requete = "SELECT CURDATE() AS curdate";
    $res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}



function deleteBase($connexion, $table){
	$requete= "DELETE FROM $table";
	$res=mysqli_query($connexion, $requete);
	return $res;
}

function getChansons($connexion, $Titre){
	$requete = "SELECT IdC, NumeroV FROM Versions NATURAL JOIN Chansons WHERE TitreC = '$Titre'";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

//supprime une chanson
function SupprChansons($connexion, $idC, $idP){
	$requete = "DELETE FROM Inclure WHERE IdC = '$idC' AND IdP = '$idP'";
	mysqli_query($connexion, $requete);
}


function getCompare($connexion, $id1, $id2){
    $requete="SELECT TitreC FROM Inclure NATURAL JOIN Chansons WHERE IdP = $id1
	INTERSECT
	SELECT TitreC FROM Inclure NATURAL JOIN Chansons WHERE IdP = $id2;";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}

function getCompare2($connexion, $colonne, $id1, $id2){
    $requete="SELECT COUNT($colonne) FROM Inclure NATURAL JOIN Chansons WHERE IdP IN ($id1,$id2) and $colonne IN
    (SELECT $colonne FROM Inclure NATURAL JOIN Chansons WHERE IdP = $id1 
    INTERSECT 
    SELECT $colonne FROM Inclure NATURAL JOIN Chansons WHERE IdP = $id2)";
	$res = mysqli_query($connexion, $requete);
	$instances = mysqli_fetch_all($res, MYSQLI_ASSOC);
	return $instances;
}
?>