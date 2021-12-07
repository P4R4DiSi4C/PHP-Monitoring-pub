<?php

	/*
	 * Auteur: David Carvalho
	 * Date: 03.02.2017
	 * Classe concernant tout ce qui est au niveau des instances mysql tel qu'afficher le statut des instances, arreter, démarrer etc
	 * INSTANCES:
	  1: DEV Duc
	  2: DEV Diadeis
	  3: QA Duc
	  4: QA Diadeis
	  5: Demo
	  6: Intradebug
	 */

	/*
	 * Classe qui gère toute les commandes serveurs concernant les instances mysql
	 */

	class mysqlInstancesManager {

		//Défini la ligne pour se connecter à la bdd
		const CONNECTSTRING = "--user=root --password=CENSORED";

		/*
		 * StartInstance
		 * @param int instance = instance mysql à démarrer
		 */

		public function StartInstance($instance) {
			$ssh = new Net_SSH2('dev');
			$ssh->login('root', 'CENSORED');
			$ssh->write("sudo mysqld_multi ".$this::CONNECTSTRING." start ".$instance."\n");
			$ssh->read('[prompt]');
			$ssh->disconnect();

		}

		/**
		 * Obtient le statut des instances
		 * @return le statut des instances
		 * */
		public function GetInstancesStatus() {
			$output = shell_exec("mysqld_multi ".$this::CONNECTSTRING." report");
			return $output;

		}

		/**
		 * StopInstance
		 * @param type $instance = instance mysql à stopper
		 */
		public function StopInstance($instance) {
			shell_exec("mysqld_multi ".$this::CONNECTSTRING." stop ".$instance."");

		}

		/**
		 * GetInstanceReport
		 * @param type $instance = instance mysql à obtenir le report
		 * @return le statut de l'instance
		 */
		public function GetInstanceReport($instance) {
			$output = shell_exec("mysqld_multi ".$this::CONNECTSTRING." report ".$instance."");
			return $output;

		}

	}
