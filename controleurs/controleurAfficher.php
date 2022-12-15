<?php 
$message = "";

// recupération des versions
$versions = getInstancesJointure($connexion, "Versions", "Chansons");
if($versions == null || count($versions) == 0) {
	$message = "Aucune version n'a été trouvée dans la base de données !";
}

