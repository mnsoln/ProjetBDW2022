<?php
		$nb = countInstances($connexion, "Versions");
		if($nb <= 0)
			$message = "Aucune version n'a été trouvée dans la base de données !";
		else
			$message = "Actuellement $nb versions dans la base.";

	?>
