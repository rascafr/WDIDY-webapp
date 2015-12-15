<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 03/12/2015
 * Time: 01:10
 */

// Modèle
include ('../../model/point/addPointAPI.php');

/**
 * Permet d'ajouter un point à une track existante
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - track_id : l'identifiant de la concernée
 * - latitude : la latitude GPS du point
 * - longitude : la longitude GPS du point
 * - date_point : la date de récupération GPS du point
 * - address : l'adresse GPS - Maps API du point. Requis même si adresse non déterminée.
 * // TODO Si adresse vide, appeler l'API Google Maps pour ajouter l'adresse
 */

// Tests
//$_POST['api_id'] = '47856230';
//$_POST['track_id'] = '12';
//$_POST['latitude'] = '47.2547';
//$_POST['longitude'] = '-0.5698';
//$_POST['date_point'] = '2015-12-03 01:12:00';
//$_POST['address'] = '10 allée des Plantier, Guy sur Loire 75200';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['track_id']) AND isset($_POST['latitude']) AND isset($_POST['longitude']) AND isset($_POST['date_point']) AND isset($_POST['address'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $track_id = strip_tags($_POST['track_id']);
    $latitude = strip_tags($_POST['latitude']);
    $longitude = strip_tags($_POST['longitude']);
    $date_point = strip_tags($_POST['date_point']);
    $address = strip_tags($_POST['address']);

    // Appel au modèle
    $resp = addPointAPI($api_id, $track_id, $latitude, $longitude, $date_point, $address);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_data = array(); // final output
        $respArray['data'] = $json_data; // returns nothing

    } else {
        $respArray['error'] = 1; // Impossible case ...
        $respArray['cause'] = 'Erreur inconnue'; // $resp['cause'];
    }

} else {
    $respArray['error'] = 1;
    $respArray['cause'] = 'Paramètres invalides.';
}

// Reply as JSON data
header('Content-Type: application/json');
echo json_encode($respArray);