<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 02/12/2015
 * Time: 01:39
 */

function getPointsAPI ($api_id, $track_id) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    $cause = '';
    $done = 0;
    $data = array();

    // Get database
    include_once(PATH.'include/sql.php');

    // Get other models
    include_once(PATH.'api/model/api/checkAPIKey.php');    // check API key
    include_once(PATH.'api/model/track/checkTrackID.php'); // check Track ID

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for a valid track
        if (checkTrackID($track_id) == 1) {

            // Search for all user tracks
            $req = $bdd->prepare("SELECT * FROM `wdidy-point` WHERE (`IDtrack` = ?)");
            $req->execute(array($track_id));
            $data = $req->fetchAll();
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