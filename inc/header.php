<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>PHP Monitoring</title>
		<script src="./assets/jquery/jquery.min.js"></script>
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/bootstrap-multiselect/css/bootstrap-select.min.css" rel="stylesheet">
		<link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="assets//sweetalert/sweetalert2.css" rel="stylesheet">
		<style>
			img {
				width: 130px;
				height: 130px;
			}

			.alignVertical {
				vertical-align: middle;
			}

			.alignHorizontal {
				text-align: center;
			}

			.green {
				color: #27ae60;
			}

			.red {
				color: #e74c3c;
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top" id="navbar">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarlinks" aria-expanded="false">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php">PHP Monitoring</a>
				</div>
				<div class="collapse navbar-collapse" id="navbarlinks">
					<ul class="nav navbar-nav">
						<li>
							<a href="index.php?page=phpinfo">PHP Info</a>
						</li>
						<li>
							<a href="index.php?page=CaDaPing">CaDaPing</a>
						</li>
						<li>
							<a href="index.php?page=prodSpareStatus">Statut de la sync. ProdSpare</a>
						</li>
						<li>
							<a href="index.php?page=instancesManager">Gérer les instances MySQL</a>
						</li>
						<li>
							<a href="index.php?page=syncDatabases">Synchroniser les bdds</a>
						</li>
						<li>
							<a href="index.php?page=bitbucketRepos">Statut du git</a>
						</li>
						<li>
							<a href="index.php?page=generalLogs">Logs</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Liens externes <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li>
									<a href="http://dev.diadeis.ch/php/isokb" target="_blank">DEV</a>
								</li>
								<li>
									<a href="http://qa.ducommun.ch/php/isokb" target="_blank">QA</a>
								</li>
								<li>
									<a href="http://intranet.ducommun.ch/php/isokb" target="_blank">PROD</a>
								</li>
								<li>
									<a href="http://dev.diadeis.ch:7990" target="_blank">BITBUCKET</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<?php

			if(isset($_GET['page']) && ($_GET['page'] == "CaDaPing" || ($_GET['page'] == "generalLogs"))) {
				echo'<div class="container-fluid">';
			}
			else {
				echo'<div class="container">';
			}

		?>

		<div class="row">