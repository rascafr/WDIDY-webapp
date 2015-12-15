<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 02/12/2015
 * Time: 01:38
 */

// Modèle
include ('../../model/track/getPointsAPI.php');

/**
 * Permet d'obtenir la liste des points pour une track donnée
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - track_id : l'identifiant de la track concernée
 */

// Test
//$_POST['api_id'] = '47856230';
//$_POST['track_id'] = '1';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['track_id'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $track_id = strip_tags($_POST['track_id']);

    // Appel au modèle
    $resp = getPointsAPI($api_id, $track_id);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_array = array();
        $json_data = array(); // final output

        foreach ($resp['data'] as $rec) {

            $json_array['IDpoint'] = $rec['IDpoint'];
            $json_array['lat'] = $rec['lat'];
            $json_array['lon'] = $rec['lon'];
            $json_array['datetime'] = $rec['datetime'];
            $json_array['address'] = $rec['address'];

            array_push($json_data, $json_array);  // push values into final array
        }

        $respArray['data'] = $json_data;

    } else {
        $respArray['error'] = 1;
        $respArray['cause'] = $resp['cause'];
    }

} else {
    $respArray['error'] = 1;
    $respArray['cause'] = 'Paramètres invalides.';
}

// Reply as JSON data
header('Content-Type: application/json');
echo json_encode($respArray);