<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 02/12/2015
 * Time: 00:03
 */

/**
 * Vérifie si un utilisateur existe et est enregistré comme actif (profil crée)
 * @param $user_id : l'identifiant de l'utilisateur à tester
 */
function checkUserKey ($user_id) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    // Get database
    include(PATH.'include/sql.php');

    // Search for a valid user
    $req=$bdd->prepare("SELECT COUNT(*) AS EXIST FROM `wdidy-user` WHERE (`IDuser` = ? AND `active` = 1)");
    $req->execute(array($user_id));
    $ret=$req->fetch();
    $req->closeCursor();

    return $ret['EXIST'];
}