<?php

/**
 * Retrourne la liste des amis de l'utilisateur
 * @param $api_id : l'identifiant de l'API utilisée à 8 chiffres. Doit exister dans la BDD et être marqué comme actif.
 * @param $user_id : l'identifiant utilisateur. Le compte doit être activé.
 */
function listFriendsAPI ($api_id, $user_id) {

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

            // Search for all user's friends with accepted request
            // The current user could be the sender (IDsender = IDuser) or the friend that accepted a request from buddy (IDfriend = IDuser)
            // We check all the friends the user has REQUESTED (asker) → IDuser = IDsender
            $req = $bdd->prepare("
                        SELECT friend.IDfriend,friend.date,user.firstname,user.lastname,user.city
                        FROM `wdidy-friends` friend, `wdidy-user` user
                        WHERE (friend.IDsender = ? AND user.IDuser = friend.IDfriend) AND friend.accepted = 1
                        ORDER BY friend.date DESC");
            $req->execute(array($user_id));
            $asUser = $req->fetchAll();
            $req->closeCursor();

            // Then we check all the users's friend request by another buddies (receiver) → IDuser = IDfriend
            // IDsender as IDfriend cause we need to get information with same name for the two arrays
            $req = $bdd->prepare("
                        SELECT friend.IDsender AS IDfriend,friend.date,user.firstname,user.lastname,user.city
                        FROM `wdidy-friends` friend, `wdidy-user` user
                        WHERE (friend.IDfriend = ? AND user.IDuser = friend.IDsender) AND friend.accepted = 1
                        ORDER BY friend.date DESC");
            $req->execute(array($user_id));
            $asFriend = $req->fetchAll();
            $req->closeCursor();

            // Finally, concats the two data arrays
            $data = array_merge($asUser, $asFriend);

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