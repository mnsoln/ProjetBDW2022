<h2>Ajout d'une chanson</h2>
<!-- Faux, j'ai fait chanson et pas version faut changer -->
<form method="post" action="#">
	<label for="TitreC"> La chanson que vous voulez rajouter : </label>
	<input type="text" name="Titre" id="TitreC" placeholder="Le titre de la chanson" required />
	<input type="text" name="Genre" id="Genre" placeholder="Genre musical de la chanson" required />
	<label for="DateCreation">Date de création : </label>
	<input type="date" name="Date de Création" id="DateCreation" placeholder="2012-11-24" />
	<input type="text" name="Nom du groupe" id="NomG" placeholder="Nom du groupe qui performe" required />
	<br/><br/>
	<input type="submit" name="boutonValider" value="Ajouter"/>
</form>

<?php if(isset($message)) { ?>
	<p style="background-color: yellow;"><?= $message ?></p>
<?php } ?>

