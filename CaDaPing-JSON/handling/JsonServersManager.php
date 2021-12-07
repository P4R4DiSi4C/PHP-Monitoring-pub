<?php

	/* Auteur: David Carvalho (CAD)
	 * Date: 20.02.2017
	 *
	 * Classe permettant de gérer tout ce qui concerne l'état des différents serveur
	 */

	class JsonServersManager {

		//Fichier json avec les bdds
		const SERVERSJSON = "./CaDaPing-JSON/files/Servers.json";

		/*
		 * Obtient tous les serveurs
		 */

		public function GetServers() {
			//Obtient le contenu du fichier de logs
			$serversContent = file_get_contents($this::SERVERSJSON);

			//Retourne le contenu encodé
			return $serversContent;

		}

		/**
		 * Méthode permettant d'ajouter un serveur
		 * @param $serverName Obtient le nom du serveur
		 * @param $serverIP Obtient l'ip du serveur
		 */
		public function AddServer($serverName, $serverIP) {
			//Tableau associatif pour définir les informations du log
			$dataArray = array(
				"serverName" => $serverName,
				"serverIP"	 => $serverIP
			);

			//Obtient le contenu du fichier des serveurs
			$serversContent	 = file_get_contents($this::SERVERSJSON);
			//Décode le fichier json contenant les serveurs
			$decodedData	 = json_decode($serversContent, 1);
			//Défini notre tableau associatif comme nouveau serveur
			$decodedData[]	 = $dataArray;
			//Ajoute le nouveau log dans le fichie des logs
			file_put_contents($this::SERVERSJSON, json_encode($decodedData));

		}

		/**
		 * Méthode permettant de mettre à jour un serveur dans le json
		 * @param type $serverOLDIP
		 * @param type $serverName
		 * @param type $serverIP
		 */
		public function ModifyServer($serverOLDIP, $serverName, $serverIP) {
			//Obtient le contenu du fichier des serveurs
			$serversContent	 = file_get_contents($this::SERVERSJSON);
			//Décode le fichier json contenant les serveurs
			$decodedData	 = json_decode($serversContent, 1);
			//Boucle pour trouver la ligne exact
			foreach($decodedData as $key => $entry) {
				if($entry['serverIP'] == $serverOLDIP) {
					$decodedData[$key]['serverName'] = $serverName;
					$decodedData[$key]['serverIP']	 = $serverIP;
				}
			}

			//Met à jour le contenu
			$newEncodedData = json_encode($decodedData);
			file_put_contents($this::SERVERSJSON, $newEncodedData);

		}

		public function ServerExists($serverIP, $serverName) {
			//Obtient le contenu du fichier des serveurs
			$serversContent	 = file_get_contents($this::SERVERSJSON);
			//Décode le fichier json contenant les serveurs
			$decodedData	 = json_decode($serversContent, 1);

			$serverExists = false;

			//Boucle pour trouver la ligne exact
			foreach($decodedData as $key => $entry) {
				if(($entry['serverIP'] == $serverIP) || ($entry['serverName'] == $serverName)) {
					$serverExists = true;
				}
			}

			return $serverExists;

		}

		/**
		 * Méthode permettant de supprimer un serveur dans le json
		 * @param type $serverIP
		 */
		public function DeleteServer($serverIP) {
			//Obtient le contenu du fichier des serveurs
			$serversContent	 = file_get_contents($this::SERVERSJSON);
			//Décode le fichier json contenant les serveurs
			$decodedData	 = json_decode($serversContent, 1);
			//Boucle pour trouver la ligne exact
			foreach($decodedData as $key => $entry) {
				if($entry['serverIP'] == $serverIP) {
					$serverName = $entry['serverName'];
					unset($decodedData[$key]);
				}
			}

			//Met à jour le contenu
			$newEncodedData = json_encode($decodedData);
			file_put_contents($this::SERVERSJSON, $newEncodedData);

			return $serverName;

		}

		/**
		 * Méthode permettant d'obtenir le nom dns d'un P
		 * @param type $serverIP
		 * @return string
		 */
		public function GetDNS($serverIP) {
			//Défini le nom DNS du serveur
			$dns = gethostbyaddr($serverIP);

			//Si le nom DNS est égal à l'ip nous affichons indisponible
			if($dns == $serverIP) {

				$dns = "Indisponible";
			}

			return $dns;

		}

	}
