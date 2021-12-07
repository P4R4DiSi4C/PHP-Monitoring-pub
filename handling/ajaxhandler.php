<?php

	chdir('../');

	//Include les fichiers de classes néc.
	set_include_path(get_include_path().PATH_SEPARATOR.'./lib/phpseclib');
	include('./lib/phpseclib/Net/SSH2.php');
	include("./class/phpFolders.php");
	include("./class/mysqlInstancesManager.php");
	include("./logs/handling/jsonLogsManager.php");

	//Créé de nouvelles instances des classes néc.
	$phpClass				 = new phpFolders();
	$mysqlInstancesManager	 = new mysqlInstancesManager();
	$jsonLogsManager		 = new jsonLogsManager();


	//Défini un tableau des instances
	$instancesArray = array(
		"DEV DUC",
		"DEV DIA",
		"QA DUC",
		"QA DIA",
		"DEMO",
		"INTRADEBUG");

	//Vérifie que tout ce que nous avons besoin est set
	if(isset($_POST['action'])) {
		if(isset($_POST['selectSpareDbs']) || isset($_POST['selectQaDbs'])) {
			//Vérifie que l'action est correcte
			if(in_array($_POST['action'], array(
					'cp_bdd',
					'cp_bdd_qa'))) {

				//Vérifie que les options séléectionnées correspondant à l'action ne sont pas vide
				if($_POST['action'] == 'cp_bdd' && !empty($_POST['selectSpareDbs'])) {
					$mysql_instance		 = $phpClass::QADUCINST; // Instance mysql = QA
					$path_to_bd_source	 = $phpClass::SPAREBDPATH; // Source = SPARE
					$nameSource			 = "SPARE";
					$path_to_bd_dest	 = $phpClass::QABDPATH; // Dest = QA
					$nameDest			 = "QA";
					$bdds				 = $_POST['selectSpareDbs'];
				}
				else if($_POST['action'] == 'cp_bdd_qa' && !empty($_POST['selectQaDbs'])) {
					$mysql_instance		 = $phpClass::DEVDIAINST; // Instance mysql = DEV
					$path_to_bd_source	 = $phpClass::QABDPATH; // Source = QA
					$nameSource			 = "QA";
					$path_to_bd_dest	 = $phpClass::DEVBDPATH; // Dest = DEV
					$nameDest			 = "DEV";
					$bdds				 = $_POST['selectQaDbs'];
				}
				else {
					die("Erreur: Aucune BDD sélectionnée.");
				}

				//Stocke le nom de l'instance
				$instanceName = $instancesArray[($mysql_instance - 1)];

				//Stop l'instance
				$mysqlInstancesManager->StopInstance($mysql_instance);
				//Ajoute dans les logs
				$jsonLogsManager->AddInstanceLog($instanceName, "stoppée pour synchronisation");
				$jsonLogsManager->AddInstanceLog($instanceName, "stoppée pour synchronisation", $jsonLogsManager::GENLOGSFILE);


				//Parcourt chaque BDD
				foreach($bdds as $bdd) {

					//Supprime la bdd actuelle du dossier de destination
					$phpClass->DeleteFolder($path_to_bd_dest.$bdd);

					# Copie le dossier source dans dest
					$ret = $phpClass->CopyFolder($path_to_bd_source.$bdd.'/ ', $path_to_bd_dest);

					if($ret != 0) {

						echo "Erreur lors de la copie de ".$bdd.". Problème de droits?</span><br>Copie de ".$path_to_bd_source.$bdd." dans ".$path_to_bd_dest."";
					}

					unset($ret);

					# Descend les droits
					$phpClass->SetFolderOwner($path_to_bd_dest.$bdd.'/');
					$phpClass->SetFolderRights($path_to_bd_dest.$bdd.'/');
				}
				sleep(3);
				//Log la synchro
				$jsonLogsManager->AddSyncLog($bdds, $path_to_bd_source, $path_to_bd_dest, $nameSource, $nameDest);
				$jsonLogsManager->AddSyncLog($bdds, $path_to_bd_source, $path_to_bd_dest, $nameSource, $nameDest, $jsonLogsManager::GENLOGSFILE);

				//Stop l'instance
				$mysqlInstancesManager->StartInstance($mysql_instance);
				//Ajoute dans les logs
				$jsonLogsManager->AddInstanceLog($instanceName, "démarrée après synchronisation");
				$jsonLogsManager->AddInstanceLog($instanceName, "démarrée après synchronisation", $jsonLogsManager::GENLOGSFILE);
			}
		}
		else {
			if(isset($_POST['idInst'])) {

				$instanceName = $instancesArray[($_POST['idInst'] - 1)];

				if($_POST['action'] == 0) {
					//STOP INSTANCE
					$mysqlInstancesManager->StopInstance($_POST['idInst']);
					//Ajoute dans les logs
					$jsonLogsManager->AddInstanceLog($instanceName, "stoppée");
					$jsonLogsManager->AddInstanceLog($instanceName, "stoppée", $jsonLogsManager::GENLOGSFILE);
				}
				elseif($_POST['action'] == 1) {
					//START INSTANCE
					$mysqlInstancesManager->StartInstance($_POST['idInst']);
					//Ajoute dans les logs
					$jsonLogsManager->AddInstanceLog($instanceName, "démarrée");
					$jsonLogsManager->AddInstanceLog($instanceName, "démarrée", $jsonLogsManager::GENLOGSFILE);
				}
				elseif($_POST['action'] == 2) {
					//RESTART INSTANCE
					$mysqlInstancesManager->StopInstance($_POST['idInst']);
					sleep(3);
					$mysqlInstancesManager->StartInstance($_POST['idInst']);
					//Ajoute dans les logs
					$jsonLogsManager->AddInstanceLog($instanceName, "redémarrée");
					$jsonLogsManager->AddInstanceLog($instanceName, "redémarrée", $jsonLogsManager::GENLOGSFILE);
				}
			}
		}
	}