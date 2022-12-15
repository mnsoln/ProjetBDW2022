<h2> Importation d'une base de données</h2>

<form method="post" action="#">
	<label for="TitreC"> La base de données de musiques que vous voulez rajouter : </label>
	<input type="text" name="Bdd" id="Bdd" value="dataset.songs100" required />
    <input type="submit" name="boutonImporter" value="Importer"/>
</form>
<br> <br> <br>
<p><?= $message ?></p>
<h4>Appuyez sur ce bouton pour vider la base de données</h4>
<form method="post" action="#">
    <input type="submit" name="boutonVider" value="Reset" style="background-color: red "/>
</form>