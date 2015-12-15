<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 03/12/2015
 * Time: 00:10
 */

// Modèle
include ('../../model/track/createTrackAPI.php');

/**
 * Permet de créer une track sur le serveur
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - user_id : l'identifiant de l'utilisateur concerné
 * - track_name : le nom de la nouvelle track à créer, encodée format URL
 */

// Tests
//$_POST['api_id'] = '47856230';
//$_POST['user_id'] = '251f563068e8636da4092490d6aeac94';
//$_POST['track_name'] = 'Boite avec les bestah';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['user_id']) AND isset($_POST['track_name'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $user_id = strip_tags($_POST['user_id']);
    $track_name = trim(strip_tags(urldecode($_POST['track_name']))); // Décodage du nom (formatté dans l'app)

    // Appel au modèle
    $resp = createTrackAPI($api_id, $user_id, $track_name);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_data = array(); // final output
        $json_data['track_id'] = $resp['data']['track_id'];
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