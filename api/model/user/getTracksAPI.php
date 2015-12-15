<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 01/12/2015
 * Time: 21:20
 */

/**
 * @param $api_id : l'identifiant de l'API utilisée à 8 chiffres. Doit exister dans la BDD et être marqué comme actif.
 * @param $user_id : l'identifiant utilisateur. Le compte doit être activé.
 */
function getTracksAPI ($api_id, $user_id) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    $cause = '';
    $done = 0;
    $data = array();

    // Get database
    include_once(PATH.'include/sql.php');

    // Get other models
    include_once(PATH.'api/model/api/checkAPIKey.php');    // check API key
    include_once(PATH.'api/model/user/checkUserKey.php');  // check User ID

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for a valid user
        if (checkUserKey($user_id) == 1) {

            // Search for all user's finished tracks
            $req = $bdd->prepare("SELECT * FROM `wdidy-track` WHERE (`IDuser` = ? AND `end` != '0000-00-00 00:00:00')");
            $req->execute(array($user_id));
            $data = $req->fetchAll();
            $req->closeCursor();
            $done = 1;
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