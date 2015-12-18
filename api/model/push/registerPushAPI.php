<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 18/12/2015
 * Time: 19:00
 */

/**
 * Permet d'ajouter un token device à la liste de l'utilisateur (enregistrement appareil)
 * @param $api_id : l'identifiant de l'API à utiliser
 * @param $user_id : l'identifiant de l'utilisateur concerné
 * @param $device_id : le token du device concerné
 */
function registerPushAPI($api_id, $user_id, $device_id) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    $cause = '';
    $done = 0;
    $data = array();

    // Get database
    include(PATH . 'include/sql.php');

    // Get other models
    include_once(PATH . 'api/model/api/checkAPIKey.php');    // check API key
    include_once(PATH . 'api/model/user/checkUserKey.php');  // check User ID

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for a valid user
        if (checkUserKey($user_id) == 1) {

            // Get device list
            $req = $bdd->prepare('SELECT `devices` FROM `wdidy-user` WHERE IDuser = ? AND active = 1');
            $req->execute(array($user_id));
            $data = $req->fetch();
            $req->closeCursor();

            // Add device identifier to existing list (if not already inside)
            $devices = $data['devices'];
            if (strpos($devices, $device_id) === false) {
                if ($devices != '') {
                    $devices .= ',';
                }
                $devices .= $device_id;
            }

            // Insert updated string into database
            $req = $bdd->prepare('UPDATE `wdidy-user` SET `devices` = ? WHERE `IDuser` = ?');
            $req->execute(array($devices, $user_id));
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