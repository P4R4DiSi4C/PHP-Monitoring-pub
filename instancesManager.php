<?php

	include("./class/phpFolders.php");
	$phpClass = new phpFolders();

?>
<link href="assets/css/consoleStyling.css" rel="stylesheet">

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">État des instances des bases de données</h3>
		</div>
		<div id="instances" class="panel-body"></div>
	</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">LOGS</h3>
		</div>
		<div class="panel-body">
			<pre class="i-has-teh-code instancesLogs"></pre>
		</div>
	</div>
</div>
