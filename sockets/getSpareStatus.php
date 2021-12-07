<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 03.02.2017
	 * Socket permettant d'obtenir l'état du spare
	 */
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	include("../class/phpFolders.php");

	//Instancie la classe phpFolders
	$phpClass = new phpFolders();

	//Vérifie si le fichier existe
	if(file_exists($phpClass::SPARESTATUS)) {
		//Retourne l'état
		echo "data: en attente\n\n";
	}
	else {
		//Retourne l'état
		echo "data: en fonction\n\n";
	}

	flush();

