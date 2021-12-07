<?php

	include("./class/bitbucketManager.php");
	$bitbucketManager = new bitbucketManager();

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Statut des dépôts sur <b>Dev</b> et <b>QA</b></h3>
		</div>
		<ul class="list-group">
			<?php

				$intranetRepos = $bitbucketManager->GetRepos("Intranet");

				foreach($intranetRepos as $i => $row) {

					echo '	<li class="list-group-item"> ';
					if($bitbucketManager->CheckQA($row['name'])) {
						echo'<span class="badge">
													<b>Q</b> <i class="fa fa-arrow-up"></i>
																'.($bitbucketManager->GetFilesToPush($row['name'])[1] != "" && $bitbucketManager->GetFilesToPush($row['name'])[1] != 0 ? $bitbucketManager->GetFilesToPush($row['name'])[1] : "0").' -
																<i class="fa fa-fw fa-arrow-down"></i>
																'.($bitbucketManager->GetFilesToPull($row['name'])[1] != "" && $bitbucketManager->GetFilesToPull($row['name'])[1] != 0 ? $bitbucketManager->GetFilesToPull($row['name'])[1] : "0").' -
																'.($bitbucketManager->GetModifiedFiles($row['name'])[1] != "" && $bitbucketManager->GetModifiedFiles($row['name'])[1] != 0 ? $bitbucketManager->GetModifiedFiles($row['name'])[1] : "0").'
												</span>';
					}
					if($bitbucketManager->CheckDev($row['name'])) {

						echo'<span class="badge">
													<b>D</b> <i class="fa fa-arrow-up"></i>
																'.($bitbucketManager->GetFilesToPush($row['name'])[0] != "" && $bitbucketManager->GetFilesToPush($row['name'])[0] != 0 ? $bitbucketManager->GetFilesToPush($row['name'])[0] : "0").' -
																<i class="fa fa-fw fa-arrow-down"></i>
																'.($bitbucketManager->GetFilesToPull($row['name'])[0] != "" && $bitbucketManager->GetFilesToPull($row['name'])[0] != 0 ? $bitbucketManager->GetFilesToPull($row['name'])[0] : "0").' -
																'.($bitbucketManager->GetModifiedFiles($row['name'])[0] != "" && $bitbucketManager->GetModifiedFiles($row['name'])[0] != 0 ? $bitbucketManager->GetModifiedFiles($row['name'])[0] : "0").'
												</span>';
					}
					echo''.$row['name'].'
									</li>';
				}

			?>
		</ul>
	</div>
</div>
