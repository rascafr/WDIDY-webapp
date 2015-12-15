<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 03/12/2015
 * Time: 00:12
 */

/**
 * Tente la création d'une track pour l'utilisateur concerné
 * @param $api_id : la clé de l'API à utiliser
 * @param $user_id : l'identifiant de l'utilisateur concerné
 * @param $track_name : le nom de la track à créer
 */
function createTrackAPI ($api_id, $user_id, $track_name) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    // Get database
    include(PATH.'include/sql.php');

    // Get other models
    include_once(PATH.'api/model/api/checkAPIKey.php');     // check API key
    include_once(PATH.'api/model/user/checkUserKey.php');   // check User ID
    include_once(PATH.'api/model/track/checkTrackName.php'); // check Track Name

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for a valid user
        if (checkUserKey($user_id) == 1) {

            // Verify if there is no same named track
            if (checkTrackName($user_id, $track_name) == -1) {

                // Get current date (database not in correct timestamp, we cannont use NOW() function)
                date_default_timezone_set('Europe/Paris');
                $date = new DateTime();
                $timestamp = $date->getTimestamp();
                $currentDate = date('Y-m-d H:i:s', $timestamp);

                // Insert track into database
                $req = $bdd->prepare("INSERT INTO `wdidy-track`(`IDuser`, `start`, `name`) VALUES (?,?,?)");
                $req->execute(array($user_id, $currentDate, $track_name));
                $req->closeCursor();

                // Get track ID
                $data['track_id'] = checkTrackName($user_id, $track_name);

                $done = 1;
            } else {
                $cause = 'Ce nom de track existe déjà : veuillez le modifier';
            }

        } else {
            $cause = 'Utilisateur inexistant ou profil non activé';
        }

    } else {
        $cause = 'Clé API inexistante ou désactivée';
    }

    // Resulting array
    $resp['success'] = $done;
    $resp['cause'] = $cause;
    $resp['data'] = $data;

    return $resp;
}