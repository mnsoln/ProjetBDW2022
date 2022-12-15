<!-- MEDINA Solène 12209453
TRAVARD Jules 12007099 -->


<?php
session_start(); // démarre ou reprend une session
ini_set('display_errors', 1); // affiche les erreurs (au cas où)
ini_set('display_startup_errors', 1); // affiche les erreurs (au cas où)
error_reporting(E_ALL); // affiche les erreurs (au cas où)
require('inc/config-bd.php'); // fichier de configuration d'accès à la BD
require('modele/modele.php'); // inclut le fichier modele
require('inc/includes.php'); // inclut des constantes et fonctions du site (nom, slogan)
require('inc/routes.php'); // fichiers de routes
$connexion = getConnexionBD(); // connexion à la BD
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?= $nomSite ?> </title>
    <link rel="stylesheet" href="style/style.css">
    <!-- ajoute une image favicon (dans l'onglet du navigateur)  + en bas doit rajouter accueil site-->
    <link rel="shortcut icon" type="image/x-icon" href="img\logoblanc2.png" />
</head>
<body>
<CENTER>
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
