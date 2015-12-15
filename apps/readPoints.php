<?php

	/**
	 * Permet de renvoyer l'ensemble des tracks au format JSON
	 */

	// Accès BDD
	include ('../include/sql.php');

	$userID = $_GET['trackID'];

	$row = $bdd->prepare('SELECT * FROM `wdidy-point` WHERE `IDtrack` = ?');
	$row->execute(array($userID));

	$json_array = array();
    $json_data = array(); // final output
    foreach ($row as $rec) {

    	$json_array['IDpoint'] = $rec['IDpoint'];
    	$json_array['lat'] = $rec['lat'];
    	$json_array['lon'] = $rec['lon'];
    	$json_array['datetime'] = $rec['datetime'];
    	$json_array['address'] = $rec['address'];

		array_push($json_data, $json_array);  // push values into final array
	}

	echo json_encode($json_data);

?>