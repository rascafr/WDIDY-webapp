<?php

/**
 * Poste un message dans une conversation entre deux personnes
 * @param $api_id : l'identifiant de l'API utilisée à 8 chiffres. Doit exister dans la BDD et être marqué comme actif.
 * @param $user_id : l'identifiant utilisateur qui poste le message. Le compte doit être activé.
 * @param $friend_id : l'identifiant de l'ami de l'utilisateur. Le compte doit être activé.
 * @param $text : Le message à poster
 */
function postMessageAPI ($api_id, $user_id, $friend_id, $text) {

    define("PATH", "/home/sites/francoisle.fr/public_html/wdidy/");

    $cause = '';
    $done = 0;
    $data = array();

    // Get database
    include_once(PATH.'include/sql.php');

    // Get other models
    include_once(PATH.'api/model/api/checkAPIKey.php');    // check API key
    include_once(PATH.'api/model/user/checkUserKey.php');  // check User ID
    include_once(PATH.'include/push.php');                 // device push function
    include_once(PATH.'api/model/push/sendPushAPI.php');   // user push API

    // Search for a valid API ID
    if (checkAPIKey($api_id) == 1) {

        // Search for a valid user
        if (checkUserKey($user_id) == 1 AND checkUserKey($friend_id) == 1) {

            // Insert message between user and his friend
            $req = $bdd->prepare("
                        INSERT INTO `wdidy-messages`(`IDsender`, `IDfriend`, `text`, `date`)
                        VALUES (?,?,?,NOW())
            ");
            $req->execute(array(
                $user_id,
                $friend_id,
                $text
                // TODO true datetime (with correct timestamp)
            ));
            $req->closeCursor();

            // Get username TODO API for that action
            $req = $bdd->prepare('SELECT * FROM `wdidy-user` WHERE (`IDuser` = ? AND `active` = 1)');
            $req->execute(array($user_id));
            $data = $req->fetch();
            $req->closeCursor();
            $userName = $data['firstname'].' '.$data['lastname']; // Friend → User ? Point of view of push / message receiver

            // Prepare a notification for the receiver
            $pushTitle = 'Nouveau message WDIDY';
            $pushMessage = $data['firstname'].' : '.substr(base64_decode($text), 0, 250);
            $pushIntent = array(
                'app_action' => 'intent.com.wdidy.app.push.conversation.new',
                'extra' => array(
                    'friend_id' => $user_id,
                    'friend_name' => $userName
                )
            );

            // PUUUUUUSSSSSH
            $cause = sendPushAPI($bdd, $friend_id, $pushTitle, $pushMessage, $pushIntent); // balek un petit peu de la réponse

            // Search for all messages between user and his friend
            $req = $bdd->prepare("
                            SELECT * FROM `wdidy-messages` WHERE
                            ((`IDsender` = ? AND `IDfriend` = ?) OR (`IDfriend` = ? AND `IDsender` = ?))
                            ORDER BY `date` DESC");
            $req->execute(array($user_id, $friend_id, $user_id, $friend_id));
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