<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 08.02.2017
	 *
	 * Classe permettant de gérer tout ce qui concerne les logs, tel que, en ajouter, les récupérer pour les afficher etc
	 */

	class jsonLogsManager {

		//Dossier du fichier de logs des instances
		const INSTANCESLOGSFILE	 = "./logs/files/instancesLogs.json";
		//Dossier du fichier de logs des synchronisation
		const SYNCLOGSFILE		 = "./logs/files/syncDatabasesLogs.json";
		//Dossier du fichier de logs général
		const GENLOGSFILE			 = "./logs/files/generalLogs.json";

		//Variable pour avoir le timestamp quand l'action est executée
		public $actionTime;
		//Variable pour stocker l'ip de l'utilisateur ayant lancer l'action
		public $userIp;
		//Variable pour stocker l'instance dont une action s'effectuera dessus
		public $instance;
		//Variable pour stocker l'action qui est executée
		public $action;
		//Variable pour le hostname du pc
		public $hostname;
		//Variables pour tout ce qui concerne les dossiers des bdds lors d'une synchronisation et la liste des bdds a sauvegarder
		public $pathSourceSrv;
		public $pathDestSrv;
		public $nameSource;
		public $nameDest;
		public $dbsList;
		public $serverName;
		public $serverIP;
		public $newServerName;
		public $newServerIP;

		/*
		 * Constructeur par défaut
		 */

		public function __construct() {
			//Défini l'ip de l'utilisateur
			$this->userIp	 = getHostByName(getHostName());
			//Défini hostname du pc
			$this->hostname	 = gethostbyaddr($_SERVER['REMOTE_ADDR']);

		}

		/**
		 * Méthode permettant d'ajouter un log d'une instance
		 * @param $instance Obtient l'instance sur laquelle l'action est effectuée
		 * @param $action Obtient l'action à executer
		 */
		public function AddInstanceLog($instance, $action, $pathLogFile = self::INSTANCESLOGSFILE) {
			//Défini l'instance
			$this->instance		 = $instance;
			//Défini l'action
			$this->action		 = $action;
			//Défini le temps auquel l'action est effectuée
			$this->actionTime	 = time();

			//Tableau associatif pour définir les informations du log
			$dataArray = array(
				"isInstance" => true,
				"instance"	 => $this->instance,
				"hostname"	 => $this->hostname,
				"ip"		 => $this->userIp,
				"action"	 => htmlentities($this->action),
				"date"		 => $this->actionTime
			);

			//Obtient le contenu du fichier de log actuel
			$instancesLogsContent	 = file_get_contents($pathLogFile);
			//Décode le fichier json contenant les logs
			$decodedData			 = json_decode($instancesLogsContent, 1);
			//Défini notre tableau associatif comme nouveau log
			$decodedData[]			 = $dataArray;
			//Ajoute le nouveau log dans le fichie des logs
			file_put_contents($pathLogFile, json_encode($decodedData));

		}

		/*
		 * Méthode permettant d'obtenir les logs des instances
		 */

		public function GetInstancesLogs() {
			//Obtient le contenu du fichier de logs
			$instancesLogsContent = file_get_contents($this::INSTANCESLOGSFILE);

			//Retourne les logs encodés encodés en json
			return $instancesLogsContent;

		}

		/*
		 * Méthode permettant d'obtenir les logs des synchros
		 */

		public function GetSyncLogs() {
			//Obtient le contenu du fichier de logs
			$syncLogsContent = file_get_contents($this::SYNCLOGSFILE);

			//Retourne les logs encodés encodés en json
			return $syncLogsContent;

		}

		/**
		 * Méthode permettant d'ajouter un log d'une synchro de bdds
		 * @param $instance Obtient l'instance sur laquelle l'action est effectuée
		 * @param $action Obtient l'action à executer
		 */
		public function AddSyncLog($dbsList, $pathSourceSrv, $pathDestSrv, $nameSource, $nameDest, $pathLogFile = self::SYNCLOGSFILE) {
			//Défini la liste de bdds
			$this->dbsList			 = $dbsList;
			//Défini le dossier source
			$this->$pathSourceSrv	 = $pathSourceSrv;
			//Défini le dossier de destination
			$this->$pathDestSrv		 = $pathDestSrv;

			//Défini le nom de la source
			$this->$nameSource = $nameSource;

			//Défini le nom de destination
			$this->$nameDest = $nameDest;

			//Défini le temps auquel l'action est effectuée
			$this->actionTime = time();

			//Tableau associatif pour définir les informations du log
			$dataArray = array(
				"isInstance" => false,
				"dbsList"	 => $this->dbsList,
				"hostname"	 => $this->hostname,
				"ip"		 => $this->userIp,
				"pathSource" => $this->$pathSourceSrv,
				"pathDest"	 => $this->$pathDestSrv,
				"nameSource" => $this->$nameSource,
				"nameDest"	 => $this->$nameDest,
				"date"		 => $this->actionTime
			);

			//Obtient le contenu du fichier de log actuel
			$syncLogsContent = file_get_contents($pathLogFile);
			//Décode le fichier json contenant les logs
			$decodedData	 = json_decode($syncLogsContent, 1);
			//Défini notre tableau associatif comme nouveau log
			$decodedData[]	 = $dataArray;
			//Ajoute le nouveau log dans le fichie des logs
			file_put_contents($pathLogFile, json_encode($decodedData));

		}

		/**
		 * Méthode permettant d'ajouter le log d'une action sur un serveur. Ajout, modif, suppression
		 * @param type $serverName
		 * @param type $serverIP
		 * @param type $action
		 * @param type $newServerName
		 * @param type $newServerIP
		 * @param type $pathLogFile
		 */
		public function AddServersLog($serverName, $serverIP, $action, $newServerName = null, $newServerIP = null, $pathLogFile = self::GENLOGSFILE) {
			$this->serverName	 = $serverName;
			$this->serverIP		 = $serverIP;
			$this->action		 = $action;
			$this->actionTime	 = time();
			$this->newServerName = $newServerName;
			$this->newServerIP	 = $newServerIP;

			$dataArray = array(
				"isServer"		 => true,
				"serverName"	 => $this->serverName,
				"serverIP"		 => $this->serverIP,
				"hostname"		 => $this->hostname,
				"ip"			 => $this->userIp,
				"action"		 => $this->action,
				"newServerName"	 => $this->newServerName,
				"newServerIP"	 => $this->newServerIP,
				"date"			 => $this->actionTime
			);

			//Obtient le contenu du fichier de log actuel
			$serversLogContent	 = file_get_contents($pathLogFile);
			//Décode le fichier json contenant les logs
			$decodedData		 = json_decode($serversLogContent, 1);
			//Défini notre tableau associatif comme nouveau log
			$decodedData[]		 = $dataArray;
			//Ajoute le nouveau log dans le fichie des logs
			file_put_contents($pathLogFile, json_encode($decodedData));

		}

		/*
		 * Obtient tous les logs général
		 */

		public function GetGenLogs() {
			//Obtient le contenu du fichier de logs
			$genLogsContent = file_get_contents($this::GENLOGSFILE);

			//Retourne les logs encodés encodés en json
			return $genLogsContent;

		}

	}
