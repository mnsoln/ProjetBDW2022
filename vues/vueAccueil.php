<main>
	
	<div><p> Bienvenue dans ce modeste site créé dans le cadre de l'UE Base de Données et Programmation Web. 
		Il a pour but de représenter un site faisant partie d'un gestionnaire de listes de musiques.</p></div>


	<div><p><?= $message ?></p></div>
	
		<br>
		Quelques statistiques...
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
		<br>
		<br>
		<table>
			<tr>
				<th>Top 5 des groupes du moment </th>
			</tr>
			<?php
			$genres=getTop5($connexion, 'Chansons', 'NomG');
			foreach ($genres as $g) {
				echo "<tr> <td> " . $g['NomG'] ."</td> </tr>";
			}
			?>
		</table>
</main>
