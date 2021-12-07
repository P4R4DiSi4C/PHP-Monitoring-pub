<?php

	/* Author: David Carvalho
	 * Date: 20.02.2017
	 * Fichier permettant de gérer les appels ajax concernant les serveurs
	 */
	chdir('../');
	include("./CaDaPing-JSON/handling/JsonServersManager.php");
	include("./logs/handling/jsonLogsManager.php");

	//instancie json manager
	$JsonServersManager	 = new JsonServersManager();
	//Instancie logs manager
	$JsonLogsManager	 = new jsonLogsManager();

	if(isset($_GET['action'])) {

		if($_GET['action'] == "ADD") {
			//Vérifie si les valeurs sont settées
			if(isset($_POST['serverName']) && isset($_POST['serverIP'])) {
				$serverName	 = $_POST['serverName'];
				$serverIP	 = $_POST['serverIP'];

				//Vérifie si les valeurs ne sont pas vides
				if(!empty($serverName) && !empty($serverIP)) {
					if(!$JsonServersManager->ServerExists($serverIP, $serverName)) {
						//Ajoute un serveur au JSON
						$JsonServersManager->AddServer($serverName, $serverIP);
						//Défini le nom DNS du serveur
						$dns = $JsonServersManager->GetDNS($serverIP);
						//Ajoute les logs
						$JsonLogsManager->AddServersLog($serverName, $serverIP, "A");

						//Génère un id unique
						$iServer = uniqid(time());

						echo '	<div id="ServerCard_'.$iServer.'" class="col-lg-3 col-md-4 col-sm-6 col-xs-12 serverCard">
									<div class="card">
										<div id="ServerStatus_'.$iServer.'" class="card-content">
											<h3 class="text-center cardTitle">'.$serverName.'</h3>
										</div>
										<div class="card-action">
											<div class="form-group">
												<label for="ServerLabelIp_'.$iServer.'">IP</label>
												<input type="text" class="form-control" id="ServerLabelIp_'.$iServer.'" value="'.$serverIP.'" readonly>
											</div>
											<div class="form-group">
												<label for="ServerLabelDns_'.$iServer.'">DNS</label>
												<input type="text" class="form-control" id="ServerLabelDns_'.$iServer.'" value="'.$dns.'" readonly>
											</div>
											<div class="form-group">
												<div class="btn-group btn-group-justified" role="group" aria-label="...">
													<div class="btn-group" role="group">
														<button class="btn btn-primary" onclick="ModifyServerModal(\''.$iServer.'\')">
															<i class="fa fa-pencil"></i> Modifier
														</button>
													</div>
													<div class="btn-group" role="group">
														<button class="btn btn-danger" onclick="DeleteServer(\''.$iServer.'\',\''.$serverIP.'\')">
															<i class="fa fa-trash"></i> Supprimer
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<script>
									var source = new EventSource("./sockets/getServersStatus.php?ip='.$serverIP.'");
									source.onmessage = function(event){

										//Check if spare is working
										if(event.data == "TRUE")
										{
											//Set text to green color
											$("#ServerStatus_'.$iServer.'").css("background-color","#2ecc71");
										}
										else
										{
											//Set text to red color
											$("#ServerStatus_'.$iServer.'").css("background-color","#e74c3c");
										}
									};
								</script>';
					}
					else {
						echo'ALREADYEXISTS';
					}
				}
			}
		}
		else if($_GET['action'] == "MOD") {
			//Vérifie si les valeurs sont settées
			if(isset($_POST['serverName']) && isset($_POST['serverIP']) && isset($_POST['modServerOLDIP']) && isset($_POST['modServerOLDNAME'])) {
				$serverName		 = $_POST['serverName'];
				$serverIP		 = $_POST['serverIP'];
				$serverOLDIP	 = $_POST['modServerOLDIP'];
				$serverOLDNAME	 = $_POST['modServerOLDNAME'];

				//Vérifie si les valeurs ne sont pas vides
				if(!empty($serverName) && !empty($serverIP) && !empty($serverOLDIP) && !empty($serverOLDNAME)) {
					//Ajoute un serveur au JSON
					$JsonServersManager->ModifyServer($serverOLDIP, $serverName, $serverIP);
					//Ajoute dans les logs
					$JsonLogsManager->AddServersLog($serverOLDNAME, $serverOLDIP, "M", $serverName, $serverIP);
				}
			}
		}
		else if($_GET['action'] == "DNSUPDATE") {
			//Vérifie si les valeurs sont settées
			if(isset($_POST['serverIP'])) {
				$serverIP = $_POST['serverIP'];
				if(!empty($serverIP)) {
					echo $JsonServersManager->GetDNS($serverIP);
				}
			}
		}
		else if($_GET['action'] == "REMOVE") {
			//Vérifie si les valeurs sont settées
			if(isset($_POST['serverIP'])) {
				$serverIP = $_POST['serverIP'];
				if(!empty($serverIP)) {
					$serverName = $JsonServersManager->DeleteServer($serverIP);
					$JsonLogsManager->AddServersLog($serverName, $serverIP, "D");
				}
			}
		}
	}

	exit();
