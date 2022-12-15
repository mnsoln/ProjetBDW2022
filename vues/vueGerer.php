<h2>Listes de Lecture :</h2>
<ul>
	<h3> Afficher le contenu d'une playlist</h3>
	<form method="post" action="#">
	<select name="NomP" id="NomP" required >
			<!-- On va chercher les playlists dans la bdd -->
		<?php
		    $optionsNomP=getInstances($connexion, "Playlists");
			foreach ($optionsNomP as $P) {
				echo "<option>" . $P['TitreP'] . "</option>";
			}
			?>
	        <option>
	</select>	<br><br>
	<input type="submit" name="boutonValider" value="Afficher"/>
	</form>
<?php
if (isset($_POST['boutonValider'])) {
	echo "<br> <br> <h3> Liste des Chansons de la Playlist </h3>";
	foreach ($TitreLP as $TitreI) {
		echo "<li>";
		echo $TitreI['TitreC'];
		echo "</li>";
	}
}
?>
<br>
<?php
if (isset($_POST['boutonValider'])) {
$DureeP = round($DureeP,2);
echo $NbC," versions dans la playlist $NP.<br>";

echo "La Playlist $NP dure $DureeP minutes.";

}
?>
<br>
<br>
<h3> Ajouter une chanson dans une playlist</h3>
	<form method="post" action="#">
	<select name="NomP" id="NomP" required >
			<!-- On va chercher les playlists dans la bdd -->
		<?php
		    $optionsNomP=getInstances($connexion, "Playlists");
			foreach ($optionsNomP as $P) {
				echo "<option>" . $P['TitreP'] . "</option>";
			}
			?>

	</select>
	<label for="TitreC"> La chanson que vous voulez rajouter dans la playlist <? $NP ?> : </label>
	<input type="text" name="TitreC" id="TitreC" placeholder="Le titre de la chanson"/>
	<input type="submit" name="boutonAjouter" value="Ajouter"/>
	</form>
<br><br/>

<h3> Supprimer une chanson d'une playlist</h3>
	<form method="post" action="#">
	<select name="NomP" id="NomP" required >
		<?php
			$optionsNomP=getInstances($connexion, "Playlists");
			foreach ($optionsNomP as $P) {
				echo "<option>" . $P['TitreP'] . "</option>";
			}
			?>
	</select>
	<label for="TitreV"> La chanson que vous voulez supprimer de la playlist <? $NP ?> : </label>
	<select name="TitreV" id="TitreV">
			<?php
			$optionNomV=getInstancesJointurePlaylist($connexion, "Playlists", "Inclure", "Chansons", "TitreC", $id);
			foreach ($optionNomV as $V) {
				echo "<option>" . $V['TitreC'] . "</option>";
			}
			?>
		</select> <br><br/>
			<input type="submit" name="boutonSupprimer" value="Supprimer"/>
	</form>


</ul>
<br>
<br>
<br>
<br>

<h3> Comparer deux playlists</h3>
    <form method="post" action="#">
        <label for="Comparer"> Comparer 2 listes de lectures :</label>
        <br>
        <br>
        <label for="Comparer"> Liste 1 :</label>
        <br>
        <select name="Nom1" id="Nom1" required >
	        <?php
		    $optionsNomP=getInstances($connexion, "Playlists");
		    foreach ($optionsNomP as $P) {
			echo "<option>" . $P['TitreP'] . "</option>";
		    }
		    ?>
            <option>
	    </select>	<br><br/>
        <label for="Comparer"> Liste 2 :</label>
        <br>
        <select name="Nom2" id="Nom2" required >
	        <?php
		    $optionsNomP=getInstances($connexion, "Playlists");
		    foreach ($optionsNomP as $P) {
			echo "<option>" . $P['TitreP'] . "</option>";
		    }
		    ?>
            <option>
	    </select>	<br><br/>
        <input type="submit" name="boutonComparer" value="Comparer"/>
	</form> 


<?php 	
echo " <br> <h3> Liste des Playlists : </h3>";
echo $message;
foreach($NomP as $Nom) { ?>
	<li>
    <?= $Nom['TitreP'];
}
?>