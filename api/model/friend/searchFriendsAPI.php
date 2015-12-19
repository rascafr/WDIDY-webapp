<?php

//$d = searchFriendsAPI('47856230', '251f563068e8636da4092490d6aeac94', 'L');
//print_r($d);

/**
 * Retourne la liste des personnes correspondant à la recherche
 * @param $api_id : l'identifiant de l'API à utiliser
 * @param $user_id : l'identifiant de l'utilisateur qui effectue la recherche (pour pouvoir indiquer qui est ami ou non avec lui)
 * @param $needle : la chaîne de caractères à rechercher dans le nom ou prénom. Ex : "Gu" peut renvoyer "GUy Plantier" ou "Jean GUy"
 */
function searchFriendsAPI ($api_id, $user_id, $needle) {

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

            // Search for user where needle :
            // Is the start of the firstname
            // Is the start of the lastname
            // Is the start of fisrtname + lastname
            $need = $needle.'%';
            // TODO : simple way here, check if MySQL is enought powerful to do that → reverse keywords (explode by space)
            // Note : MySQL already search ignoring case with LIKE keyword
            $req=$bdd->prepare("
                    SELECT * FROM `wdidy-user`
                    WHERE ((`firstname` LIKE ?) OR (`lastname` LIKE ?) OR (CONCAT(`firstname`, ' ', `lastname`) LIKE ?)) AND `active` = 1
                    ");
            $req->execute(array($need, $need, $need));
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