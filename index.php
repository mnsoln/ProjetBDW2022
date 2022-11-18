<?php
session_start(); // démarre ou reprend une session
ini_set('display_errors', 1); // affiche les erreurs (au cas où)
ini_set('display_startup_errors', 1); // affiche les erreurs (au cas où)
error_reporting(E_ALL); // affiche les erreurs (au cas où)
require('modele/modele.php'); // inclut le fichier modele
require('inc/includes.php'); // inclut des constantes et fonctions du site (nom, slogan)
require('inc/routes.php'); // fichiers de routes
// $connexion = getConnexionBD(); // connexion à la BD
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?= $nomSite ?> </title>
    <link href="style/style.css" ref="stylesheet" media="all" type="text/css">
    <!-- ajoute une image favicon (dans l'onglet du navigateur)  + en bas doit rajouter accueil site-->
    <link rel="shortcut icon" type="image/x-icon" href="img\Deezifyblanc.png" />
	<style>

body {
    background-color: #ffffff;
}

header,
nav,
main,
footer {
    padding: 1em 0;
}

header {
    background-color: #ffffff;
    text-align: center;
    border-radius: 2em;
}

#divCentral {
    display: flex;
	justify-content: space-between;
	align-items: stretch;
    margin: auto;
	box-sizing: border-box;
	height: 100%;
}

nav {
	flex : 4;
    background-color: #d373b3;
    padding-left: 1em;
    padding-right: 1em;
	margin-left: 2em;
    line-height: 2em;
	border-radius: 3em
}

nav a {
    color: #b82676;
    text-decoration: underline;
}

nav a:visited {
    color: #612847;
    text-decoration: none;
}

main {
	flex : 10;
    background-color: #EEEEEE;
    padding-left: 6em;
}

footer {
	display: flex;
	justify-content: space-around;
    background-color: #e77c31;
    border-radius: 2em;
}


	</style>
</head>
<body>
    <?php include('static/header.php'); ?>
    <div id="divCentral">
        <?php include('static/menu.php'); ?>
		<main>
		<?php
		$controleur = 'controleurAccueil'; // par défaut, on charge accueil.php
		$vue = 'vueAccueil'; // par défaut, on charge accueil.php
		if(isset($_GET['page'])) {
			$nomPage = $_GET['page'];
			if(isset($routes[$nomPage])) { // si la page existe dans le tableau des routes, on la charge
				$controleur = $routes[$nomPage]['controleur'];
				$vue = $routes[$nomPage]['vue'];
			}
		}
		include('controleurs/' . $controleur . '.php');
		include('vues/' . $vue . '.php');
		?>
		</main>
	</div>
    <?php include('static/footer.php'); ?>
</body>
</html>
