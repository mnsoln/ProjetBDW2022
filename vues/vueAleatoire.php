<h2>Création d'une playlist aléatoire</h2>

<form method="post" action="#">
	<label for="nomGenre"> Le genre que vous désirez : </label>
	<select name="Genre" id="Genre">
		<option selected>Aucun en particulier</option>
	<?php
		$optionsGenre=getInstancesPrecisDistinct($connexion, "Chansons", "Genre");
		foreach ($optionsGenre as $g) {
			echo "<option>" . $g['Genre'] . "</option>";
		}
		?>
	</select> 
	<label for="Stats"> Préférence sur les statistiques : </label> 
	<select name="Stats" id="Stats">
		<option selected>Aucune en particulier</option>
		<option>LastPlayed</option>
		<option>Playcount</option>
		<option>Skipcount</option>
	</select>
	<label for="DateCreation">Durée de la playlist : </label>
	<input type="int" name="DureeP" id="DureeP" value="20" min="2" max="100" required/> minutes <br>
	<br>
	<label for="TitreP">Nom de la playlist : </label>
	<input type="text" name="TitreP" id="TitreP" placeholder="Le nom que vous souhaitez donner à la playlist (facultatif)">
	<br/><br/>
	<input type="submit" name="boutonValider" value="Ajouter"/>
</form>

<br>
<table>
		<tr>
			<th>Top 5 des genres du moment </th>
		</tr>
		<?php
		$genres=getTop5($connexion, 'Chansons', 'Genre');
		foreach ($genres as $g) {
			echo "<tr> <td> " . $g['Genre'] ."</td> </tr>";
		}
		?>
</table>


<?php if(isset($message)) { ?>
	<p style="background-color: yellow;"><?= "Erreur lors de l'insertion de la chanson." ?></p>
<?php } ?>
