<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 02/12/2015
 * Time: 01:41
 */

/**
 * Teste si la track selectionnée existe
 * @param $track_id : l'identifiant de la track à tester
 */
function checkTrackID ($track_id) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    // Get database
    include(PATH.'include/sql.php');

    // Search for a valid API ID
    $req=$bdd->prepare("SELECT COUNT(*) AS EXIST FROM `wdidy-track` WHERE (`IDtrack` = ?)");
    $req->execute(array($track_id));
    $ret=$req->fetch();
    $req->closeCursor();

    return $ret['EXIST'];
}