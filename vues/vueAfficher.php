<h2>Liste des versions :</h2>
<ul>
<?php foreach($versions as $version) { 
    $fich=$version['NomFichier']?>
	<li>
    <?= $version['TitreC']?> par <?= $version['NomG']?>  d'une durée de <?= $version['DureeV'] ?> minutes. Le genre est <?= $version['Genre'];
if ($version['NomFichier'] == null) {
        echo ". </li> <br>";
     } else {
        echo " et se trouve dans le fichier " . $fich . ". </li> <br>";
     }

} ?>
</ul>
