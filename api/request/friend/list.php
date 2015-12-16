<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 16/12/2015
 * Time: 15:16
 */

// Modèle
include ('../../model/friend/listFriendsAPI.php');

/**
 * Permet d'obtenir la liste des amis d'un utilisateur particulier
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - user_id : l'identifiant de l'utilisateur concerné
 */

$_POST['api_id'] = '47856230';
$_POST['user_id'] = '251f563068e8636da4092490d6aeac94';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['user_id'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $user_id = strip_tags($_POST['user_id']);

    // Appel au modèle
    $resp = listFriendsAPI($api_id, $user_id);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_array = array();
        $json_data = array(); // final output

        foreach ($resp['data'] as $rec) {

            $json_array['IDfriend'] = $rec['IDfriend'];
            $json_array['firstname'] = $rec['firstname'];
            $json_array['lastname'] = $rec['lastname'];
            $json_array['date'] = $rec['date'];

            array_push($json_data, $json_array);  // push values into final array
        }

        $respArray['data'] = $json_data;

    } else {
        $respArray['error'] = 1; // Impossible case ...
        $respArray['cause'] = $resp['cause'];
    }

} else {
    $respArray['error'] = 1;
    $respArray['cause'] = 'Paramètres invalides.';
}

// Reply as JSON data
header('Content-Type: application/json');
echo json_encode($respArray);