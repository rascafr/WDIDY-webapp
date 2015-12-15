<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 03/12/2015
 * Time: 00:35
 */

/**
 * Retourne l'ID d'une track en fonction de son nom et de l'utilisateur auquel elle est associée
 * @param $user_id : l'identifiant de l'utilisateur
 * @param $track_name : le nom de la track à tester
 * @return -1 si la track n'existe pas, l'ID de la track sinon
 */
function checkTrackName ($user_id, $track_name) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    // Get database
    include(PATH.'include/sql.php');

    // Search for a valid API ID
    $req=$bdd->prepare("SELECT `IDtrack` FROM `wdidy-track` WHERE (`IDuser` = ? AND `name` = ?)");
    $req->execute(array($user_id, $track_name));
    $ret=$req->fetch();
    $req->closeCursor();


    return empty($ret) ? -1 : $ret['IDtrack'];
}