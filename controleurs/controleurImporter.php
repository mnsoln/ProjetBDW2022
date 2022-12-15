<?php
if(isset($_POST['boutonImporter'])) { // si formulaire soumis

    //récupération des données
    $bdd = $_POST['Bdd']; // on récupère la base de données à importer
    $songs= getSongs($connexion, $bdd); // on récupère les données à l'intérieur de la bdd

    $requeteprop= " INSERT INTO Proprietes (NomPropriete) VALUES ('Filesize'), ('Playcount'), ('LastPlayed'), ('Skipcount')"; // on initialise les variables propriétés
    $res = mysqli_query($connexion, $requeteprop);

    foreach ($songs as $song){
        $sep = array('/', 'and', 'And', ','); // énumération des séparateurs possibles
        $genres=explode ( ';', str_replace($sep, ';',$song['genre'])); // séparation des différents genres
        $genre=$genres[0];
        $titre = addslashes($song ['title']); // on utilise addslashes sur les variables pour éviter des erreurs
        $artiste = addslashes($song['artist']);
        $album= addslashes($song['album']);
        importationGCA($connexion, $artiste, $album, $titre, $genre); //importation dans les tables groupes, chansons et albums

        if ($song['compilation'] = 1) { //verification si l'album est un album compilation
            $requete21 = "INSERT INTO Albums_Compilation (TitreA) VALUES ('$album')"; 
            $res = mysqli_query($connexion, $requete21);
        }

        $id = getIDwhere($connexion, $titre); // récupération de l'id de la chanson créé précédemment
        $n = rand(1, 20);
        $duree= round($song['length']/60, 2); // on transforme la durée en minutes avant de l'enregistrer
        $nomfich = addslashes($song['filename']);
        $filesize = addslashes($song['filesize']);
        $playcount = addslashes($song['playcount']);
        $lastplayed = round($song['lastplayed']/60, 2); // on transforme la durée lastplayed en minutes avant de l'enregistrer

        // on vérifie si la chanson a déjà été jouée
        if ($lastplayed < 0) { 
            $lastplayed = 'NULL'; //on transforme le -1 en null pour une meilleure compréhension
        }

        $skipcount = addslashes($song['skipcount']);
        importationVA($connexion, $id, $n, $duree, $nomfich, $playcount, $skipcount, $lastplayed, $filesize); //importation dans versions et avoir
        
    }
   echo " <h4> L'importation est terminée. </h4>";
}

//comptage des versions
$nb = countInstances($connexion, "Versions");
if ($nb <= 0) {
    $message = "Aucune version n'a été trouvée dans la base de données !";
} else {
    $message = "Actuellement $nb versions dans la base.";
}


 // reset de la base si appuyé
if(isset($_POST['boutonVider'])) {  
   deleteBase($connexion, "Albums_Studio");
   deleteBase($connexion, "Enregistrer");
   deleteBase($connexion, "Posseder");
   deleteBase($connexion, "Interpreter");
   deleteBase($connexion, "Est_en_relation");
   deleteBase($connexion, "Faire_partie");
   deleteBase($connexion, "Inclure");
   deleteBase($connexion, "Participer");
   deleteBase($connexion, "sont_enregistres");
   deleteBase($connexion, "Albums_Live");
   deleteBase($connexion, "Albums_Compilation");
   deleteBase($connexion, "Lieux");
   deleteBase($connexion, "Albums");
   deleteBase($connexion, "Playlists");
   deleteBase($connexion, "Musiciens");
   deleteBase($connexion, "Avoir");
   deleteBase($connexion, "Versions");
   deleteBase($connexion, "Proprietes");
   deleteBase($connexion, "Chansons");
   deleteBase($connexion, "Groupes_de_musique");
}

?>