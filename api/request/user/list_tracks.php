<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 01/12/2015
 * Time: 21:25
 */

// Modèle
include ('../../model/user/getTracksAPI.php');

/**
 * Permet d'obtenir la liste des tracks d'un utilisateur sous forme d'un JSON
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - user_id : l'identifiant de l'utilisateur concerné
 */

// Tests
//$_POST['api_id'] = '47856230';
//$_POST['user_id'] = '251f563068e8636da4092490d6aeac94';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['user_id'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $user_id = strip_tags($_POST['user_id']);

    // Appel au modèle
    $resp = getTracksAPI($api_id, $user_id);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_array = array();
        $json_data = array(); // final output

        foreach ($resp['data'] as $rec) {

            $json_array['IDtrack'] = $rec['IDtrack'];
            $json_array['start'] = $rec['start'];
            $json_array['end'] = $rec['end'];
            $json_array['name'] = $rec['name'];

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