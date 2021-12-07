<?php

	/*
	 * Auteur: David Carvalho
	 * Date: 08.02.2017
	 * Classes permettant d'obtenir des informations de notre serveur bitbucket interne
	 */

	class bitbucketManager {

		/**
		 * Méthode pour obtenir les répos d'un projet spécifié
		 * @param string Nom d'un projet
		 * @return array Répos d'un projet
		 */
		public function GetRepos($project) {
			//Initie curl
			$repos = curl_init();

			//Défini les options pour le curl
			curl_setopt($repos, CURLOPT_URL, "http://localhost:7990/rest/api/1.0/projects/".$project."/repos/");
			curl_setopt($repos, CURLOPT_TIMEOUT, 30); //timeout après 30 seconds
			curl_setopt($repos, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //méthode d'auth
			curl_setopt($repos, CURLOPT_USERPWD, "admin:CENSORED"); //User et password
			curl_setopt($repos, CURLOPT_RETURNTRANSFER, TRUE); //Pour ne pas afficher le résultat
			//Décode les répos qui sont encodé en json
			$decodedRepos = json_decode(curl_exec($repos), 1);

			//Ferme la connexion
			curl_close($repos);

			//Retourne le tableau des repos
			return $decodedRepos['values'];

		}

		/**
		 * Obtient les fichiers modifiés d'un repos qui n'ont pas été commit.
		 * @param stromg Nom du repo
		 * @return Tableau avec fichiers modifiés d'un repo spécifié
		 */
		public function GetModifiedFiles($repo) {
			//Obtient les fichiers modifiés et pas commit sur dev en les filtrant pour obtenir que ce qu'on compte: "modified:"
			$outputdev	 = shell_exec("cd /web/dev/diadeis/public_html/php/".$repo." && git status");
			$matchesdev	 = preg_match_all('/(modified)/', $outputdev, $matchesdev);

			//Obtient les fichiers modifiés et pas commit sur qa en les filtrant pour obtenir que ce qu'on compte: "modified:"
			$outputqa	 = shell_exec("cd /web/dev/diadeis/public_html/php/".$repo." && git status");
			$matchesqa	 = preg_match_all('/(modified)/', $outputqa, $matchesqa);

			//Envoie les 2 tableaux de fichiers dans un tableau
			$outArray = array(
				$matchesdev,
				$matchesqa);

			return $outArray;

		}

		/**
		 * Obtient le nombre de fichiers à tirer
		 * @param string Nom du repo
		 * @return Tableau avec le nombre de fichiers à tirer
		 */
		public function GetFilesToPull($repo) {
			//Obtient les fichiers à tirer sur dev
			$outputdev	 = shell_exec("cd /web/dev/diadeis/public_html/php/".$repo." && git rev-list HEAD...origin/master --count");
			//Obtient les fichiers à tirer sur qa
			$outputqa	 = shell_exec("cd /web/qa/ducommun/public_html/php/".$repo." && git rev-list HEAD...origin/master --count");

			//Envoie les 2 tableaux de fichiers dans un tableau
			$outArray = array(
				$outputdev,
				$outputqa);

			return $outArray;

		}

		/**
		 * Obtient le nombre de fichiers à pousser
		 * @param string Nom du repo
		 * @return Tableau avec le nombre de fichiers à pousser
		 */
		public function GetFilesToPush($repo) {
			//Obtient les fichiers à pousser sur dev
			$outputdev	 = shell_exec("cd /CENSORED/public_html/php/".$repo." && git cherry -v");
			//Filtre pour n'avoir que ce qu'il y a un "+"
			$matchesdev	 = preg_match_all('/^[+]/', $outputdev, $matchesdev);

			//Obtient les fichiers à pousser sur qa
			$outputqa	 = shell_exec("cd /CENSORED/public_html/php/".$repo." && git cherry -v");
			//Filtre pour n'avoir que ce qu'il y a un "+"
			$matchesqa	 = preg_match_all('/^[+]/', $outputqa, $matchesqa);

			//Envoie les 2 tableaux de fichiers dans un tableau
			$outArray = array(
				$matchesdev,
				$matchesqa);

			return $outArray;

		}

		/**
		 * Méthode permettant de vérifier si DEV au moins un fichier modifié ou à tirer ou à pousser
		 * @param string Nom du repo
		 * @return boolean
		 */
		public function CheckDev($repo) {
			//Mets le bool à false par défaut
			$isReturn = false;

			//Check si il y au moins un fichier modifié
			if($this->GetModifiedFiles($repo)[0] != "" && $this->GetModifiedFiles($repo)[0] != 0) {
				$isReturn = true;
			}
			//Check si il y au moins un fichier à tirer
			elseif($this->GetFilesToPull($repo)[0] != "" && $this->GetFilesToPull($repo)[0] != 0) {
				$isReturn = true;
			}
			//Check si il y au moins un fichier à pousser
			elseif($this->GetFilesToPush($repo)[0] != "" && $this->GetFilesToPush($repo)[0] != 0) {
				$isReturn = true;
			}

			return $isReturn;

		}

		/**
		 * Méthode permettant de vérifier si QA au moins un fichier modifié ou à tirer ou à pousser
		 * @param string Nom du repo
		 * @return boolean
		 */
		public function CheckQA($repo) {
			//Mets le bool à false par défaut
			$isReturn = false;

			//Check si il y au moins un fichier modifié
			if($this->GetModifiedFiles($repo)[1] != "" && $this->GetModifiedFiles($repo)[1] != 0) {
				$isReturn = true;
			}
			//Check si il y au moins un fichier à tirer
			elseif($this->GetFilesToPull($repo)[1] != "" && $this->GetFilesToPull($repo)[1] != 0) {
				$isReturn = true;
			}
			//Check si il y au moins un fichier à pousser
			elseif($this->GetFilesToPush($repo)[1] != "" && $this->GetFilesToPush($repo)[1] != 0) {
				$isReturn = true;
			}

			return $isReturn;

		}

	}
