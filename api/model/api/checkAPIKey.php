<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 02/12/2015
 * Time: 00:00
 */

/**
 * Teste si l'API sélectionnée existe et est activée
 * @param $api_id : l'identifiant de l'API à tester
 */
function checkAPIKey ($api_id) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    // Get database
    include(PATH.'include/sql.php');

    // Search for a valid API ID
    $req=$bdd->prepare("SELECT COUNT(*) AS EXIST FROM `api_id_list` WHERE (`api_id` = ? AND `enabled` = 1)");
    $req->execute(array($api_id));
    $ret=$req->fetch();
    $req->closeCursor();
    $valid = $ret['EXIST'];

    // Increments API usage counter if API is correct
    // (because this function is called before each usage)
    if ($valid == 1) {
        $req=$bdd->prepare("UPDATE `api_id_list` SET `call_usage` = `call_usage` + 1 WHERE `api_id` = ?");
        $req->execute(array($api_id));
        $req->closeCursor();
    }

    return $valid;
}