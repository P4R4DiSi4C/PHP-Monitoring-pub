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

	$instancesLogs	 = $jsonLogsManager->GetInstancesLogs();
	$instancesLogs	 = json_decode($instancesLogs, 1);


	$sReturn = '';

	if($instancesLogs != "") {

		foreach($instancesLogs as $innerArray) {
			//print_r($instancesLogs);
			$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Instance ".$innerArray['instance']." ".$innerArray['action']." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
		}
	}
	else {


		$sReturn = 'Aucun logs à afficher';
	}


	//Retourne la dernière date de synchro
	echo "data: ".$sReturn."\n\n";

	flush();
