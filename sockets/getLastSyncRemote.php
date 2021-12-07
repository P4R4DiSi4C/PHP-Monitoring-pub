<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 03.02.2017
	 * Socket permettant d'obtenir l'état du spare
	 */
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	//Obtient le contenu du fichier
	$lastsync = file_get_contents("http://intranet.ducommun.ch/lastsync_remote.txt");

	//Retourne la dernière date de synchro
	echo "data: ".$lastsync."\n\n";

	flush();
