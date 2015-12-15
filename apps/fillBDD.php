<?php

	// Accès BDD
	include ('../include/sql.php');

	$data = file_get_contents('ligneA.points');
	$pos = explode(PHP_EOL, $data);

	$cnt = 0;
	foreach ($pos as $posGPS) {
		$dt = explode(' ', $posGPS);
		$lon = str_replace('-.', '-0.', $dt[0]);
		$lat = $dt[1];

		echo $cnt.' -> '.$lat.', '.$lon.'<br>';

		$req = $bdd->prepare('INSERT INTO `wdidy-point`(`IDtrack`, `lat`, `lon`, `datetime`, `address`) VALUES (1,?,?,"2015-02-02 15:00:00", "Guy Plantier");');
		$req->execute(array($lat, $lon));
		$req->closeCursor();

		$cnt++;
	}

	echo $cnt.' lines inserted';

?>