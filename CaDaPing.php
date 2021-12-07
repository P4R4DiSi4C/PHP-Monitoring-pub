<?php

	/* Author: David Carvalho
	 * Date: 20.02.2017
	 * Page permettant d'avoir un aperçu de l'état des serveurs
	 */
	include("./class/Ping.php");
	include("./CaDaPing-JSON/handling/JsonServersManager.php");

	//Instancie json manager
	$JsonServersManager = new JsonServersManager();

	//Obtient les serveurs
	$serversList = $JsonServersManager->GetServers();
	$serversList = json_decode($serversList, 1);

	//Trie par noms de serveurs
	function sortServerNames($a, $b) {
		return strnatcmp(strtolower($a['serverName']), strtolower($b['serverName']));

	}

	usort($serversList, 'sortServerNames');

?>
<link href="assets/css/cards.css" rel="stylesheet">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="row">
		<div id="CaDaPing" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h1 class="text-center text-muted">Statut des serveurs</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<button type="button" class="btn btn-default" data-toggle="modal" data-target="#addServerModal">
				<i class="fa fa-plus"></i> Ajouter un serveur
			</button>
		</div>
	</div>
	<div id="serversList" class="row">
		<?php

			//Vérifie que la liste de serveurs n'est pas vide
			if($serversList != "") {
				//Compteur
				$iServer = 0;

				//Boucle foreach parcourant les différents serveurs
				foreach($serversList as $innerArray) {
					$dns = $JsonServersManager->GetDNS($innerArray['serverIP']);

					echo '	<div id="ServerCard_'.$iServer.'" class="col-lg-3 col-md-4 col-sm-6 col-xs-12 serverCard">
								<div class="card">
									<div id="ServerStatus_'.$iServer.'" class="card-content">
										<h3 class="text-center cardTitle">'.$innerArray['serverName'].'</h3>
									</div>
									<div class="card-action">
										<div class="form-group">
											<label for="ServerLabelIp_'.$iServer.'">IP</label>
											<input type="text" class="form-control" id="ServerLabelIp_'.$iServer.'" value="'.$innerArray['serverIP'].'" readonly>
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
													<button class="btn btn-danger" onclick="DeleteServer(\''.$iServer.'\',\''.$innerArray['serverIP'].'\')">
														<i class="fa fa-trash"></i> Supprimer
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<script>
								var source = new EventSource("./sockets/getServersStatus.php?ip='.$innerArray['serverIP'].'");
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

					$iServer++;
				}
			}

		?>

	</div>
</div>
<div id="modifyServerModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"></button>
				<h4 class="modal-title">Modifier un serveur</h4>
			</div>
			<div class="modal-body">
				<form id="modifyServerForm">
					<div class="form-group">
						<label for="serverName">Nom du serveur</label>
						<input type="text" class="form-control" id="modServerName" name="serverName" autofocus="autofocus" placeholder="Nom du serveur" value='.$sServerName.'>
					</div>
					<div class="form-group">
						<label for="serverIP">Adresse IP</label>
						<input type="text" class="form-control" id="modServerIP" name="serverIP" placeholder="Adresse IP" value='.$sServerIP.'>
					</div>
					<input type="hidden" id="modServerOLDIP" name="modServerOLDIP" value="">
					<input type="hidden" id="modServerID" name="modServerID" value="">
					<input type="hidden" id="modServerOLDNAME" name="modServerOLDNAME" value=""></form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="pull-left btn btn-default" onclick="return ModifyServer($('#modifyServerForm'));">Modifier</button>
				<button type = "button" class = "btn btn-default" data-dismiss = "modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<div id="addServerModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"></button>
				<h4 class="modal-title">Ajouter un serveur</h4>
			</div>
			<div class="modal-body">
				<form id="addServerForm">
					<div class="form-group">
						<label for="serverName">Nom du serveur</label>
						<input type="text" class="form-control" id="serverName" name="serverName" autofocus="autofocus" placeholder="Nom du serveur">
					</div>
					<div class="form-group">
						<label for="serverIP">Adresse IP</label>
						<input type="text" class="form-control" id="serverIP" name="serverIP" placeholder="Adresse IP">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="pull-left btn btn-default" onclick="return AddServer($('#addServerForm'));">Ajouter</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

