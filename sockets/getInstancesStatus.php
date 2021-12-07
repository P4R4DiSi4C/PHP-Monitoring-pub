<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 03.02.2017
	 * Socket permettant d'envoyer un tableau json avec l'état des instances.
	 */
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	include("../class/mysqlInstancesManager.php");

	//Créé une nouvelle instance de la classe mysqlInstancesManager
	$mysqlInstancesManager = new mysqlInstancesManager();

	//Obtient le statut des instances
	$instances = $mysqlInstancesManager->GetInstancesStatus();

	//Filtre le résultat pour n'avoir que "mysqldX is running / is not running"
	preg_match_all("/(mysqld)[1-6](( is running)|( is not running))/", $instances, $matches);

	//Encode le statut des instances en json
	$instancesData = json_encode($matches[0]);

	//Traduit le is running et le is not running
	$instancesData	 = str_replace("is running", "fonctionne", $instancesData);
	$instancesData	 = str_replace("is not running", "ne fonctionne pas", $instancesData);

	//Défini un tableau des instances
	$instancesArray = array(
		"DEV DUC",
		"DEV DIA",
		"QA DUC",
		"QA DIA",
		"DEMO",
		"INTRADEBUG");

	//Parcout chaque instance et remplace "mysqldX" par le nom de l'instance
	for($i = 0; $i <= count($matches); $i++) {
		$instancesData = str_replace("mysqld".($i + 1), $instancesArray[$i], $instancesData);
	}

	// Envoie le data
	echo "data: ".$instancesData."\n\n";
	flush();

