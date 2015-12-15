<!DOCTYPE <!DOCTYPE html>
<html>
	<head>
		<title>WDIDY</title>
		<meta charset="utf-8" />

		<style>

			html {
				width: 100%;
				height: 100%;
				background: #333;
			}

			.resume_address {
				/*display: inline-block;*/
				color: white;
				padding-top: -0.5em;
			}

			.resume_point {
				width: 1em;
				height: 1em;
				border-radius: 50%;
				border: 0.25em solid white;
				display: inline-block;
			}

			.resume_addressText {
				display: inline-block;
				position: absolute;
				margin-left: 0.5em;
				padding: 0;
			}

			.resume_detail {
				color: #ccc;
			}

			.resume_linkLine {
				background: white;
				width: 0.25em;
				height: 3em;
				margin-left: 0.625em;
				padding: 0;
				display: inline-block;
			}

			.resume_detailText {
				display: inline-block;
				position: absolute;
				margin-left: 1.125em;
				padding: 0;
			}

		</style>

	</head>
	<body>

	<?php

		// Accès BDD
		include ('../include/sql.php');

		$trackID = 2;

		$row = $bdd->prepare('SELECT COUNT(*) AS NBLINES FROM `wdidy-point` WHERE `IDtrack` = ?');
		$row->execute(array($trackID));
		$data = $row->fetch();
		$row->closeCursor();

		$max = $data['NBLINES'];

		$row = $bdd->prepare('SELECT * FROM `wdidy-point` WHERE `IDtrack` = ?');
		$row->execute(array($trackID));

		$i = 0;
		$strLines = '';

	    foreach ($row as $rec) {

	    	$flag = 1;

	    	$strLines .= '<div class="resume_address">';
	    	$strLines .= '<div class="resume_point"></div><div class="resume_addressText">'.$rec['address'].'</div>';
	    	$strLines .= '</div>';

	    	$strLines .= '<div class="resume_detail">';
	    	$strLines .= '<div class="resume_linkLine" ';
	    	if ($i == $max - 1) {
	    		$strLines .= 'style="opacity:0;"';
	    	}
	    	$strLines .= '></div>';
	    	$strLines .= '<div class="resume_detailText">'.$rec['lat'].'<br>'.$rec['lon'].'</div>';
    		$strLines .= '</div>';

    		$i++;
		}

		echo $strLines;

		$row->closeCursor();

	?>

	</body>
</html>