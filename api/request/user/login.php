<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 17/12/2015
 * Time: 11:22
 */

// Modèle
include ('../../model/user/loginUserAPI.php');

/**
 * Permet de valider la connexion d'un utilisateur
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - email : l'email de l'utilisateur
 * - password : le mot de passe utilisateur hashé en sha256 + salt
 */

// Tests
// $_POST['api_id'] = '47856230';
// $_POST['email'] = 'rascafr@gmail.com';
// $_POST['password'] = '61e9f109c2246ea104b532d1fb44a062d8f9c2b59e57c1aef5e3e70146a4ba70';

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['email']) AND isset($_POST['password'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $email = strip_tags(urldecode($_POST['email']));
    $password = strip_tags($_POST['password']);

    // Appel au modèle
    $resp = loginUserAPI($api_id, $email, $password);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_array = array();
        $json_data = array(); // final output

        $json_array['IDuser'] = $resp['data']['IDuser'];
        $json_array['firstname'] = $resp['data']['firstname'];
        $json_array['lastname'] = $resp['data']['lastname'];
        $json_array['country'] = $resp['data']['country'];
        $json_array['city'] = $resp['data']['city'];

        $respArray['data'] = $json_array;

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