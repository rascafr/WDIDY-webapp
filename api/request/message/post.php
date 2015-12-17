<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 16/12/2015
 * Time: 21:25
 */

// Modèle
include ('../../model/message/postMessageAPI.php');

define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

/**
 * Permet de poster un message dans une conversation entre deux personnes
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - user_id : l'identifiant de l'utilisateur concerné
 * - friend_id : l'identifiant de l'ami l'utilisateur concerné
 * - text : le message encodé (base64) // TODO test base64 smileys / emoji ?
 */

//$_POST['api_id'] = '47856230';
//$_POST['user_id'] = '251f563068e8636da4092490d6aeac94';
//$_POST['friend_id'] = 'a98640811bd2d60205a1346b0f6c886c';
//$_POST['text'] = urlencode('Test test test !');

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['user_id']) AND isset($_POST['friend_id']) AND isset($_POST['text'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $user_id = strip_tags($_POST['user_id']);
    $friend_id = strip_tags($_POST['friend_id']);
    $text = trim(strip_tags($_POST['text']));

    // Appel au modèle
    $resp = postMessageAPI($api_id, $user_id, $friend_id, $text);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_array = array();
        $json_data = array(); // final output

        foreach ($resp['data'] as $rec) {

            $json_array['IDmessage'] = $rec['IDmessage'];
            $json_array['IDsender'] = $rec['IDsender'];
            $json_array['IDfriend'] = $rec['IDfriend'];
            $json_array['text'] = $rec['text'];
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