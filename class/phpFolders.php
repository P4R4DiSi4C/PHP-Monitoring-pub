<?php

	/**
	 * Auteur: David Carvalho
	 * Date: 03.02.2017
	 * Classe concernant tout ce qui est dossiers de base de données mysql.
	 *
	 * INSTANCES
	  1: DEV Duc
	  2: DEV Diadeis
	  3: QA Duc
	  4: QA Diadeis
	  5: Demo
	  6: Intradebug
	 */
	class phpFolders {

		//DOSSIERS
		const SPARESTATUS	 = "/CENSORED/.htaccess";
		const SPAREBDPATH	 = "/CENSORED/ducommun/";
		const QABDPATH	 = "/CENSORED/ducommun/";
		const DEVBDPATH	 = "/CENSORED/diadeis/";
		const QAFILES		 = "/CENSORED/php/";
		const SPAREFILES	 = "/CENSORED/public_html/php/";
		//INSTANCES
		const DEVDUCINST	 = 1;
		const DEVDIAINST	 = 2;
		const QADUCINST	 = 3;
		const QADIAINST	 = 4;
		const DEMOINST	 = 5;
		const INTRADEBUG	 = 6;

		/*
		 * Liste les dossiers
		 */

		public function ListeDossier($mydir) {
			//Tableau qui contiendra les bases de données.
			$filelist = array();

			//vérifie si le dossier fourni est vide
			if(empty($mydir)) {

				die("<span class='txt_error'>Erreur, le dossier n'est pas renseigné</span>");
			}

			//Ouvre le dossier
			if($dir = opendir($mydir)) {

				//Boucle les fichiers
				while(($file = readdir($dir)) !== false) {

					//Fait les vérifications pour vérifier que c'est bien un dossier de BDD
					if(is_dir($mydir.$file."/") AND $file != ".." AND $file != "." AND $file != "phpMyAdmin" AND $file != "mysql" AND $file != "performance_schema") {
						//Ajoute à la lise
						$filelist[] = $file;
					}
				}
				//Ferme le dossier
				closedir($dir);
			}

			return $filelist;

		}

		/**
		 * Remove a specified folder
		 * @param type $folder
		 */
		public function DeleteFolder($folder) {
			system('rm -R '.$folder);

		}

		/**
		 * Copy a folder to a destination
		 * @param type $sourceFolder
		 * @param type $destFolder
		 * @return type
		 */
		public function CopyFolder($sourceFolder, $destFolder) {
			system('cp -Rp '.$sourceFolder.$destFolder, $ret);
			return $ret;

		}

		/**
		 * Set the owner of a folder
		 * @param type $folder
		 */
		public function SetFolderOwner($folder) {
			system('chown -R mysql:mysql '.$folder);

		}

		/**
		 * Set the rights of a folder
		 * @param type $folder
		 */
		public function SetFolderRights($folder) {
			system('chmod -R 777 '.$folder);

		}

	}
