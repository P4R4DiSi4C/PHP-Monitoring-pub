/*
 * Author: David Carvalho (CAD)
 * Date: 03.02.2017
 * - Création des différentes vérifications pour désactiver les selects et bouttons ou les activer.
 * - Création de deux socket SSE Recevant heures:minutes et un deuxième recevant l'état des instances MySql
 */

$(document).ready(function(){
	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});
});

//Autofocus an input in modal
$('.modal').on('shown.bs.modal',function(){
	$(this).find('[autofocus]').focus();
});

//Check if browser supports SSE
if(typeof (EventSource) !== "undefined"){
	/*
	 * Gets the time of the server and checks if its time for spare to backup from prod
	 */
	var source = new EventSource("./sockets/getTime.php");
	source.onmessage = function(event){

		var midStart = "13:50";
		var midEnd = "14:10";

		var morningStart = "08:20";
		var morningEnd = "08:40";

		var morningStart2 = "09:50";
		var morningEnd2 = "10:10";

		var endDayStart = "14:50";
		var endDayEnd = "15:10";

		//Checks for the different periods of the day when spare backups from prod
		if((event.data > midStart) && (event.data < midEnd) || (event.data > morningStart) && (event.data < morningEnd) || (event.data > morningStart2) && (event.data < morningEnd2) || (event.data > endDayStart) && (event.data < endDayEnd))
		{
			//Disable all selects and buttons
			$(".selectpicker").attr("disabled","disabled").selectpicker('refresh');
		}
		else
		{
			//Enable all selects and buttons back
			$(".selectpicker").removeAttr("disabled").selectpicker('refresh');
		}
	};

	/*
	 * Gets the status of the instances encoded in json and displays them with an image and text
	 */
	var source = new EventSource("./sockets/getInstancesStatus.php");
	source.onmessage = function(event){

		//Parse the json data recieved
		var data = JSON.parse(event.data);

		//Clear the instaces div
		$("#instances").html("");

		//Loop through the array of data
		for(var i = 0; i < data.length; i++)
		{
			//Set by default the text color green and a db icon with a check mark
			var pColor = "green";
			var imgInst = "databaseqa.svg";
			var isUp = "disabled";
			var isDown = "enabled";

			//Check if the instance is not running by checking the text recieved via json
			if(data[i].indexOf("ne fonctionne pas") > -1)
			{
				//Set the text color to red
				pColor = "red";

				//Set a db image with a red cross
				imgInst = "databaseko.svg";

				//Set a boolean to check if we disable the button or not
				isUp = "enabled";
				isDown = "disabled";
			}

			//Set the div and image of the instance
			var instanceDiv = "<div class='col-lg-4 col-md-6 col-sm-6 col-xs-12 alignHorizontal'><img src='./img/" + imgInst + "' />";

			// Création des boutons d'action
			var instanceBtn = '<div class="btn-group">\n\
									<button id="' + (i + 1) + '" name="btnStartInst" onClick="ManageInstance(this.id,1)" class="btn btn-success btn-xs" ' + isUp + '>\n\
										<i class="fa fa-play"></i>\n\
									</button>\n\
									<button id="' + (i + 1) + '" name="btnStopInst" onClick="ManageInstance(this.id,0)" class="btn btn-danger btn-xs" ' + isDown + '>\n\
										<i class="fa fa-stop" ></i>\n\
									</button>\n\
									<button id="' + (i + 1) + '" name="btnRestartInst" onClick="ManageInstance(this.id,2)" class="btn btn-warning btn-xs" ' + isDown + '>\n\
										<i class="fa fa-refresh"></i>\n\
									</button>\n\
								</div>';

			//Set the text of the instance
			var instanceText = "<p class='" + pColor + "'>" + data[i] + "</p>" + instanceBtn + "<br><br></div>";

			//Append the instance div and text to the instances general div
			$("#instances").append(instanceDiv + instanceText);
		}


	};


	/*
	 * Gets the status of the spare
	 */
	var source = new EventSource("./sockets/getSpareStatus.php");
	source.onmessage = function(event){
		//Check if spare is working
		if(event.data == "en fonction")
		{
			//Set text to green color
			$("#sparestatus").addClass('green');
		}
		else
		{
			//Set text to red color
			$("#sparestatus").addClass('red');
		}
		//Write the data
		$('#sparestatus').html(event.data);
	};

	/*
	 * Get when the last sync with remote happened
	 */
	var source = new EventSource("./sockets/getLastSyncRemote.php");
	source.onmessage = function(event){
		//Get and write the last sync of spare in the website
		$('#lastsync').html(event.data);
	};

	/*
	 * Get instances logs
	 */
	var source = new EventSource("./sockets/getInstancesLogs.php");
	source.onmessage = function(event){

		$(".instancesLogs").html(event.data);

	};

	/*
	 * Get sync logs
	 */
	var source = new EventSource("./sockets/getSyncLogs.php");
	source.onmessage = function(event){

		$(".syncLogs").html(event.data);

	};

	/*
	 * Get general logs
	 */
	var source = new EventSource("./sockets/getGenLogs.php");
	source.onmessage = function(event){

		$(".generalLogs").html(event.data);

	};

}

/*
 * Function to manage the instances.
 * Start/Stop/Restat
 *
 * @param idInstance = Id of the instance
 * @param action = What to do
 */
function ManageInstance(idInstance,action)
{
	//Set a text to confirm for each action
	if(action == 0)
	{
		textAction = "Voulez-vous vraiment arrêter l'instance ?";
	}
	else if(action == 1)
	{
		textAction = "Voulez-vous vraiment démarrer l'instance ?";
	}
	else if(action == 2)
	{
		textAction = "Voulez-vous vraiment redémarrer l'instance ?";
	}

	//Use sweet alert to have a nice modal and prevent the user from doing anything else than waiting
	swal.queue([
		{
			title: 'Êtes-vous sûr ?',
			text: textAction,
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Oui !',
			showLoaderOnConfirm: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			preConfirm: function(){
				return new Promise(function(resolve){
					//Use ajax to send the data
					$.ajax({
						type: "POST",
						url: "../handling/ajaxhandler.php",
						data: {
							idInst: idInstance,
							action: action
						},
						success: function(){
							resolve();
							return false;
						}
					});
				});
			}
		}
	]);
}

/**
 * Function to send the data in ajax for the databases to copy
 * @param form = Get the form that defines what server the user wants to save from
 */
function CopyDatabases(form){

	//Use sweetalert to have a nice modal and prevent the user from doing anything else than waiting
	swal.queue([
		{
			title: 'Êtes-vous sûr ?',
			text: "Voulez-vous vraiment copier la/les bases de données sélectionnée/s ?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Oui !',
			showLoaderOnConfirm: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			preConfirm: function(){
				return new Promise(function(resolve){
					//Use ajax to post what is needed
					$.ajax({
						url: '../handling/ajaxhandler.php',
						type: 'POST',
						data: form.serialize(),
						async: true,
						success: function(){
							$('#selectSpareDbs').val("");
							$('#selectQaDbs').val("");
							$("#sparetoqabtn").attr("disabled","disabled").button('refresh');
							$("#qatodevbtn").attr("disabled","disabled").button('refresh');

							resolve();
							return false;
						}
					});
				});
			}
		}
	]);
	return false;
}

/**
 * Function to add a server to the json
 * @param form = Get the form that defines what server the user wants to add
 */
function AddServer(form){
	$.ajax({
		url: '../handling/serversAjaxHandler.php?action=ADD',
		type: 'POST',
		data: form.serialize(),
		async: true
	}).done(function(data){
		if(data == "ALREADYEXISTS")
		{
			swal(
				'Erreur ajout',
				'Le serveur existe déjà !',
				'error'
				);
		}
		else
		{
			swal({
				title: 'Ajout réussi !',
				text: 'Fermeture...',
				timer: 2000,
				allowOutsideClick: false,
				allowEscapeKey: false,
				showConfirmButton: false
			}).then(
				function(){},
				// handling the promise rejection
					function(dismiss){
						if(dismiss === 'timer'){

						}
					}
				);



				$("#serversList").append(data);

				SortServersByName();

				$('#addServerModal').modal('toggle');


				return false;
			}
	}).fail(function(){
		alert("Erreur lors de l'ajout.");
	});
}

/*
 * Function to sort the servers by name
 * @returns {undefined}
 */
function SortServersByName()
{
	$('.serverCard').sort(function(a,b){
		if(($(a).find(".cardTitle").text().toLowerCase()) < ($(b).find(".cardTitle").text().toLowerCase())){
			return -1;
		}
		else{
			return 1;
		}
	}).appendTo('#serversList');

	$(":input",$('#addServerModal')).each(function()
	{
		this.value = "";
	});
}

/*
 * Function to open a modal to modify a server
 * @param {type} sServerIp
 * @returns {undefined}
 */
function ModifyServerModal(sServerID){

	var serverName = $("#ServerCard_" + sServerID).find(".cardTitle").text();
	var serverIP = $("#ServerLabelIp_" + sServerID).val();

	$("#modServerName").val(serverName);
	$("#modServerIP").val(serverIP);
	$("#modServerOLDNAME").val(serverName);
	$("#modServerOLDIP").val(serverIP);
	$("#modServerID").val(sServerID);
	$("#modifyServerModal").modal();
}

/**
 * Function to modify a server
 * @param {type} form
 */
function ModifyServer(form)
{
	$.ajax({
		url: '../handling/serversAjaxHandler.php?action=MOD',
		type: 'POST',
		data: form.serialize(),
		async: true
	}).done(function(data){

		var serverID = $('#modServerID').val();
		var newServerIP = $('#modServerIP').val();
		var newServerName = $('#modServerName').val();

		$("#ServerCard_" + serverID).find(".cardTitle").html(newServerName);
		$("#ServerLabelIp_" + serverID).val(newServerIP);

		$.ajax({
			url: '../handling/serversAjaxHandler.php?action=DNSUPDATE',
			type: 'POST',
			data: form.serialize(),
			async: true
		}).done(function(data){
			$("#ServerLabelDns_" + serverID).val(data);
			SortServersByName();
			$('#modifyServerModal').modal('toggle');

			swal({
				title: 'Modification réussie !',
				text: 'Fermeture...',
				timer: 2000,
				allowOutsideClick: false,
				allowEscapeKey: false,
				showConfirmButton: false
			}).then(
				function(){},
				// handling the promise rejection
					function(dismiss){
						if(dismiss === 'timer'){

						}
					}
				);
			});

	});
}

/**
 * Method to delete a server by having his ip
 * @param {type} serverID
 * @param {type} serverIP
 * @returns {undefined}
 */
function DeleteServer(serverID,serverIP)
{
	swal({
		title: 'Voulez-vous vraiment supprimer ce serveur ?',
		text: "Vous ne pourrez pas revenir en arrière !",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Oui, supprimer le serveur !'
	}).then(function(){
		$.ajax({
			url: '../handling/serversAjaxHandler.php?action=REMOVE',
			type: 'POST',
			data: {
				serverIP: serverIP
			},
			async: true
		}).done(function(){
			$("#ServerCard_" + serverID).remove();

			swal(
				'Serveur supprimé !',
				'Le serveur a bien été supprimé.',
				'success'
				);
		});
	});
}


/**
 * Check the value of the select. Disable or enable the buttons
 */
$('#selectSpareDbs').on('changed.bs.select',function(){
	if($(this).val() != "")
	{
		$("#sparetoqabtn").removeAttr("disabled").button('refresh');
	}
	else
	{
		$("#sparetoqabtn").attr("disabled","disabled").button('refresh');
	}
}
);
/**
 * Check the value of the select. Disable or enable the buttons
 */
$('#selectQaDbs').on('changed.bs.select',function(){

	if($(this).val() != "")
	{
		$("#qatodevbtn").removeAttr("disabled").button('refresh');
	}
	else
	{
		$("#qatodevbtn").attr("disabled","disabled").button('refresh');
	}
});