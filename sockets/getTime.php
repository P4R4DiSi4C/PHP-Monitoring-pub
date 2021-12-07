<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 03.02.2017
	 * Socket permettant d'obtenir l'heure et minutes actuelles et d'envoyer au JS
	 */

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	// Get the current time on server
	$currentTime = date("H:i", time());

	// Send it in a message
	echo "data: ".$currentTime."\n\n";
	flush();
