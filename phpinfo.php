<?php

	ob_start();
	phpinfo();
	$sPhpInfos = ob_get_contents();
	ob_end_clean();

	$sPhpInfos = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $sPhpInfos);

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">PHP Info</h3>
		</div>
		<div class="panel-body">
			<style>
				table {
					width: 100%;
					border: 1px solid #ccc;
					background: #fff;
					padding: 1px;
				}

				td, th {
					border: 1px solid #FFF;
					font-size: 12px;
					padding:4px 8px;
				}

				h1 {
					font-size: 24px;
					margin: 10px;
				}

				h2 {
					font-size: 22px;
					color: #0B5FB4;
					text-align: left;
					margin: 25px auto 5px auto;
				}

				hr {
					background-color: #A9A9A9;
					color: #A9A9A9;
				}

				.e, .v, .vr {
					color: #333;
					font-size: 11px;
				}

				.e {
					background-color: #eee;
					min-width: 250px;
				}

				.h {
					background-color: #0B5FB4;
					color: #fff;
				}
				.v {
					background-color: #F1F1F1;
					-ms-word-break: break-all;
					word-break: break-all;
					word-break: break-word;
					-webkit-hyphens: auto;
					-moz-hyphens: auto;
					hyphens: auto;
				}
				img {
					display:none;
				}
			</style>
			<?php echo $sPhpInfos;?>
		</div>
	</div>
</div>