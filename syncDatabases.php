<?php

	include("./class/phpFolders.php");
	$phpClass = new phpFolders();

?>
<link href="assets/css/consoleStyling.css" rel="stylesheet">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Outil de synchronisation de bases de données</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 alignHorizontal">
					<a href="http://spare.ducommun.ch/" target="_blank">
						<img src="./img/databasespare.svg"></img>
						<h3>SPARE</h3>
					</a>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<form id="copybddspare" name="cp_bdd">
						<input type="hidden" name="action" value="cp_bdd" />
						<div class="form-group">
							<select id="selectSpareDbs" class="form-control selectpicker" name="selectSpareDbs[]" multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count > 2" disabled>
								<?php

									$spareDbs = $phpClass->ListeDossier($phpClass::SPAREBDPATH);

									foreach($spareDbs as $database) {

										echo "<option value='".$database."'>".$database."</option>";
									}

								?>
							</select>
						</div>
						<div class="form-group">
							<button id="sparetoqabtn" type="submit" name="send" onclick="return CopyDatabases($('#copybddspare'));" class="btn btn-primary btn-block" disabled>
								<span class="iconlabelhor">COPIER VERS </span><i class="fa fa-arrow-right" aria-hidden="true"></i>
							</button>
						</div>
					</form>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 alignHorizontal">
					<a href="http://qa.ducommun.ch/" target="_blank">
						<img src="./img/databasevalidation.svg"></img>
						<h3>QA</h3>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 alignHorizontal">
					<a href="http://qa.ducommun.ch/" target="_blank">
						<img src="./img/databasevalidation.svg"></img>
						<h3>QA</h3>
					</a>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<form id="copybddsqa" name="cp_bdd_qa" >
						<input type="hidden" name="action" value="cp_bdd_qa" />
						<div class="form-group">
							<select id="selectQaDbs" class="form-control selectpicker" name="selectQaDbs[]" multiple data-live-search="true" data-actions-box="true" data-selected-text-format="count > 2" disabled>
								<?php

									$qaDbs = $phpClass->ListeDossier($phpClass::QABDPATH);

									foreach($qaDbs as $database) {

										echo "<option value='".$database."'>".$database."</option>";
									}

								?>
							</select>
						</div>
						<div class="form-group">
							<button id="qatodevbtn" type="submit" name="send" onclick="return CopyDatabases($('#copybddsqa'));" class="btn btn-primary btn-block" disabled>
								<span class="iconlabelhor">COPIER VERS </span><i class="fa fa-arrow-right" aria-hidden="true"></i>
							</button>
						</div>
					</form>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 alignHorizontal">
					<a href="http://dev.diadeis.ch/" target="_blank">
						<img src="./img/databasedev.svg"></img>
						<h3>DEV</h3>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">LOGS</h3>
		</div>
		<div class="panel-body">
			<pre class="i-has-teh-code syncLogs"></pre>
		</div>
	</div>
</div>

