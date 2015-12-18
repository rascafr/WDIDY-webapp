<?php

/**
 * Envoie une notification push à un utilisateur
 * @warn NE DOIT PAS ÊTRE UTILISÉ DEPUIS AUTRE PART QUE DEPUIS UN PHP D'API SERVEUR (pas de vérif API)
 * @pre objet BDD, push inclu dans le fichier d'appel
 * @param $bdd : L'objet PDO à utiliser
 * @param $user_id : L'utilisateur qui recevra la notification push
 * @param $title : Le titre de la notification
 * @param $message : Le message de la notification
 * @param $intent : L'objet intent TODO : documenter le fonctionnement de cet objet ...
 * @return string : La réponse GCM
 */
function sendPushAPI ($bdd, $user_id, $title, $message, $intent) {

    // Recherche des tokens utilisateur
    $req = $bdd->prepare('SELECT `devices` FROM `wdidy-user` WHERE IDuser = ? AND active = 1');
    $req->execute(array($user_id));
    $data = $req->fetch();
    $req->closeCursor();

    // Add device identifier to existing list (if not already inside)
    $devices = $data['devices'];

    // Explode it
    $devices_array = explode(',', $devices);

    // TODO single by sigle to handle bad devices / tokens

    // Send push
    return pushUser($devices_array, $title, $message, $intent);

}