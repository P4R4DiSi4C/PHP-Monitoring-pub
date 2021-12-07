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

	$genLogs = $jsonLogsManager->GetGenLogs();
	$genLogs = json_decode($genLogs, 1);


	$sReturn = '';

	if($genLogs != "") {

		foreach($genLogs as $innerArray) {
			if(isset($innerArray['isInstance'])) {
				if($innerArray['isInstance'] == true) {
					$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Instance ".$innerArray['instance']." ".$innerArray['action']." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
				}
				else {
					$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Synchro de ".$innerArray['nameSource']." (".$innerArray['pathSource'].") à ".$innerArray['nameDest']." (".$innerArray['pathDest'].") pour les bdds ".implode(", ", $innerArray['dbsList'])." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
				}
			}
			if(isset($innerArray['isServer'])) {
				if($innerArray['isServer'] == true) {
					//Ajout
					if($innerArray['action'] == "A") {
						$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Ajout du serveur ".$innerArray['serverName']." avec l'ip ".$innerArray['serverIP']." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
					}
					//Modif
					elseif($innerArray['action'] == "M") {
						$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Modification du serveur ".$innerArray['serverName']." avec l'ip ".$innerArray['serverIP']." en ".$innerArray['newServerName']." avec l'ip ".$innerArray['newServerIP']." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
					}
					//Delete
					elseif($innerArray['action'] == "D") {
						$sReturn .= $innerArray['hostname']." (".$innerArray['ip']."): Supression du serveur ".$innerArray['serverName']." avec l'ip ".$innerArray['serverIP']." le ".date('d.m.Y', $innerArray['date'])." à ".date('H:i:s', $innerArray['date'])."<br>";
					}
				}
			}
		}
	}
	else {


		$sReturn = 'Aucun logs à afficher';
	}


	//Retourne la dernière date de synchro
	echo "data: ".$sReturn."\n\n";

	flush();
