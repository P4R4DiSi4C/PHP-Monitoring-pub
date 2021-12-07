<?php

	// Niveau d'erreur à afficher
	error_reporting(E_ALL ^ E_STRICT);

	// Récupère la page à charger
	$sPage = (isset($_GET["page"]) ? $_GET["page"] : null);

	// Selon la valeur du paramètre
	switch($sPage) {

		//Tous les logs
		case "generalLogs":
			$sFileToInclude = "generalLogs.php";
			break;

		// Dépôts BitBuckets
		case "bitbucketRepos":
			$sFileToInclude = "bitbucketRepos.php";
			break;

		// Synchronisation des bdd
		case "syncDatabases":
			$sFileToInclude = "syncDatabases.php";
			break;

		// Gestionnaire des instances MySQL
		case "instancesManager":
			$sFileToInclude = "instancesManager.php";
			break;

		case "phpinfo":
			$sFileToInclude = "phpinfo.php";
			break;

		case "CaDaPing":
			$sFileToInclude = "CaDaPing.php";
			break;

		// Par défaut, c'est le status de ProdSpareStatus
		default:
		case "prodSpareStatus":
			$sFileToInclude = "prodSpareStatus.php";
			break;
	}

	// Inclus le header
	require_once("./inc/header.php");

	// Inclus la page à charger
	require_once($sFileToInclude);

	// Inclus le footer
	require_once("./inc/footer.php");
