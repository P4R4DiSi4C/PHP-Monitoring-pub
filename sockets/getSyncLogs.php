<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 03.02.2017
	 * Socket permettant d'obtenir l'état du spare
	 */
	header('Content-Type: text/event-stream; charset=utf-8');
	header('Cache-Control: no-cache');

	chdir('../');

	include("./logs/handling/jsonLogsManager.php");

	$jsonLogsManager = new jsonLogsManager();

	$syncLogs	 = $jsonLogsManager->GetSyncLogs();
	$syncLogs	 = json_decode($syncLogs, 1);


	$sReturn = '';

	if($syncLogs != "") {

		foreach($syncLogs as $innerArray) {
			//print_r($instancesLogs);
			$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Synchro de ".$innerArray['nameSource']." (".$innerArray['pathSource'].") à ".$innerArray['nameDest']." (".$innerArray['pathDest'].") de ".implode(", ", $innerArray['dbsList'])." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
		}
	}
	else {


		$sReturn = 'Aucun logs à afficher';
	}


	//Retourne la dernière date de synchro
	echo "data: ".$sReturn."\n\n";

	flush();
