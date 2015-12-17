<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 17/12/2015
 * Time: 11:10
 */

/**
 * @param $api_id : l'identifiant de l'API à utiliser
 * @param $email : l'email de l'utilisateur
 * @param $password : password le mot de passe utilisateur hashé en sha256 + salt
 */
function loginUserAPI ($api_id, $email, $password) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    $cause = '';
    $done = 0;
    $data = array();

    // Get database
    include(PATH.'include/sql.php');

    // Get other models
    include_once(PATH.'api/model/api/checkAPIKey.php');    // check API key

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for auser
        $req = $bdd->prepare('SELECT * FROM `wdidy-user` WHERE (`email` = ? AND `password` = ? AND `active` = 1)');
        $req->execute(array($email, $password));
        $data = $req->fetch();
        $req->closeCursor();

        if (count($data) != 1) {
            $done = 1;
        } else {
            $cause = 'Utilisateur inexistant ou profil non activé.'.$_SERVER["DOCUMENT_ROOT"];;
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