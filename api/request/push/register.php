<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 18/12/2015
 * Time: 19:11
 */

// Modèle
include ('../../model/push/registerPushAPI.php');

define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

/**
 * Permet d'enregistrer un appareil dans la BDD en tant que receveur push (API GCM)
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - user_id : l'identifiant de l'utilisateur concerné
 * - device_id : l'identifiant de l'appareil concerné
 */

//$_POST['api_id'] = '47856230';
//$_POST['user_id'] = '251f563068e8636da4092490d6aeac94';
//$_POST['device_id'] = '2464664tytubvbj';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['user_id']) AND isset($_POST['device_id'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $user_id = strip_tags($_POST['user_id']);
    $device_id = strip_tags($_POST['device_id']);

    // Appel au modèle
    $resp = registerPushAPI($api_id, $user_id, $device_id);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

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