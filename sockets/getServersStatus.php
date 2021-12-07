<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 20.02.2017
	 * Socket permettant d'obtenir l'état de XEN
	 */
	chdir("../");

	include('./class/Ping.php');

	$sIp = $_GET['ip'];

	$pingManager = new Ping($sIp);

	$latency = $pingManager->ping();

	if($latency !== false) {
		$isUp = "TRUE";
	}
	else {
		$isUp = "FALSE";
	}

	header('Content-Type: text/event-stream; charset=utf-8');
	header('Cache-Control: no-cache');

	//Retourne la dernière date de synchro
	echo "data: ".$isUp."\n\n";

	flush();
