<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 03/12/2015
 * Time: 00:56
 */

/**
 * Permet d'ajouter un point dans la track sélectionnée
 * @param $api_id : la clé d'API à utiliser
 * @param $track_id : track_id : l'identifiant de la track concernée
 * @param $latitude : la longitude en format double : 47.142563
 * @param $longitude : la latitude en format double : -0.2547
 * @param $date_point : la date du point en format YYYY-MM-JJ HH:MM:SS
 * @param $address : l'adresse du point en tant que chaîne de caractère
 */
function addPointAPI ($api_id, $track_id, $latitude, $longitude, $date_point, $address) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    $cause = '';
    $done = 0;
    $data = array();

    // Get database
    include_once(PATH.'include/sql.php');

    // Get other models
    include_once(PATH.'api/model/api/checkAPIKey.php');    // check API key
    include_once(PATH.'api/model/track/checkTrackID.php'); // check Track ID

    // Skip return line
    $address = str_replace("\n", ", ", $address);

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for a valid track
        if (checkTrackID($track_id) == 1) {

            // Add the point into database
            $req = $bdd->prepare("INSERT INTO `wdidy-point`(`IDtrack`, `lat`, `lon`, `datetime`, `address`) VALUES (?,?,?,?,?)");
            $req->execute(array($track_id, $latitude, $longitude, $date_point, $address));
            $req->closeCursor();

            // TODO Check whether the point has been added to the database

            // End date
            $req = $bdd->prepare("UPDATE `wdidy-track` SET `end` = ? WHERE `IDtrack` = ?");
            $req->execute(array($date_point, $track_id));
            $req->closeCursor();

            $done = 1;

        } else {
            $cause = 'Track inexistante';
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