<?php

	include("./class/phpFolders.php");
	$phpClass = new phpFolders();

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">État de la synchronisation</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="media">
						<div class="media-left">
							<a href="http://intranet.ducommun.ch/php/isokb/" target="_blank" class="alignHorizontal">
								<img class="media-object" src="./img/server.svg">
							</a>
						</div>
						<div class="media-body alignVertical alignHorizontal">
							<h4>PROD à REMOTE</h4>
							<h4 class="media-heading">Synchronisé le<br><span class="text-muted" id="lastsync"></span></h4>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="media">
						<div class="media-left">
							<a href="http://spare.ducommun.ch/" target="_blank" class="alignHorizontal">
								<img class="media-object" src="./img/databasespare.svg">
							</a>
						</div>
						<div class="media-body alignVertical alignHorizontal">
							<h4>SPARE</h4>
							<h4 class="media-heading">Le serveur est<br><span id="sparestatus"></span></h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
