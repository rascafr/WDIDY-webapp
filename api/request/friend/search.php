<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 19/12/2015
 * Time: 16:45
 */

/**
 * Permet de recherche des utilisateurs depuis un morceau de leur nom / prénom
 * Paramètres POST :
 * - api_id : l'identifiant de l'API à utiliser
 * - user_id : l'identifiant courant de l'utilisateur
 * - needle : la chaîne de caractères à rechercher
 */

//$_POST['api_id'] = '47856230';
//$_POST['user_id'] = '251f563068e8636da4092490d6aeac94';
//$_POST['needle'] = 'Le';

// Modèle
include ('../../model/friend/searchFriendsAPI.php');

define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

// Paramètres POST
if (isset($_POST['api_id']) AND isset($_POST['user_id']) AND isset($_POST['needle'])) {

    // Sécurisation
    $api_id = strip_tags($_POST['api_id']);
    $user_id = strip_tags($_POST['user_id']);
    $needle = trim(strip_tags(urldecode(strip_tags($_POST['needle']))));

    // Appel au modèle
    $resp = searchFriendsAPI($api_id, $user_id, $needle);

    // Check des données reçues
    if ($resp['success'] == 1) {
        $respArray['error'] = 0;

        $json_array = array();
        $json_data = array(); // final output

        foreach ($resp['data'] as $rec) {

            $json_array['IDuser'] = $rec['IDuser'];
            $json_array['firstname'] = $rec['firstname'];
            $json_array['lastname'] = $rec['lastname'];
            $json_array['country'] = $rec['country'];
            $json_array['city'] = $rec['city'];

            if (!file_exists(PATH.'picts/'.$rec['IDuser'].'.jpg')) {
                $json_array['imgLink'] = 'guy.jpg'; // Default picture
            } else {
                $json_array['imgLink'] = $rec['IDuser'].'.jpg'; // True profile picture
            }

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