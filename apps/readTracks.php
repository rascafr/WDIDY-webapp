<?php

	/**
	 * Permet de renvoyer l'ensemble des tracks au format JSON
	 */

	// Accès BDD
	include ('../include/sql.php');

	$userID = $_GET['userID'];

	$row = $bdd->prepare('SELECT * FROM `wdidy-track` WHERE `IDuser` = ?');
	$row->execute(array($userID));

	$json_array = array();
    $json_data = array(); // final output
    foreach ($row as $rec) {

    	$json_array['IDtrack'] = $rec['IDtrack'];
    	$json_array['start'] = $rec['start'];
    	$json_array['end'] = $rec['end'];
    	$json_array['name'] = $rec['name'];

		array_push($json_data, $json_array);  // push values into final array
	}

	echo json_encode($json_data);

?>