<h2>Ajout d'une chanson</h2>

<form method="post" action="#">
	<label for="TitreC"> La chanson que vous voulez rajouter : </label>
	<input type="text" name="TitreV" id="TitreV" placeholder="Le titre de la chanson" required />
	<label for="Genre"> Le genre : </label>
	<select name="Genre" id="Genre">
		<!-- On va chercher les genres dans la bdd -->
		<?php
		$optionsGenre=getInstancesPrecisDistinct($connexion, "Chansons", "Genre");
		foreach ($optionsGenre as $g) {
			echo "<option>" . $g['Genre'] . "</option>";
		}
		?>
	</select>
	<label for="DateCreation">Date de création : </label>
	<input type="date" name="DateCreation" id="DateCreation" value="2012-11-24" max="2022-12-10"/>
	<label for="DureeV">Durée : </label>
	<input type="int" name="DureeV" id="DureeV" value="3.2" min="0.5" max="7" /> minutes <br> <br>
	<label for="GrouvesV"> Le nom de l'artiste :</label>
	<select name="GroupesV" id="GroupesV" required >
		<!-- On va chercher les groupes dans la bdd -->
	<?php
		$optionsGroupe=getInstancesPrecisDistinct($connexion, "Chansons", "NomG");
		foreach ($optionsGroupe as $g) {
			echo "<option>" . $g['NomG'] . "</option>";
		}
		?>
	</select>	<br><br/>
	<input type="text" name="NomFichier" id="NomFichier" placeholder="Le nom du fichier de la chanson" required />

	<input type="submit" name="boutonValider" value="Ajouter"/>
</form>

<?php if(isset($message)) { ?>
	<p style="background-color: yellow;"><?= "Erreur lors de l'insertion de la chanson." ?></p>
<?php } ?>

