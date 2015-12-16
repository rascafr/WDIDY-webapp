<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 16/12/2015
 * Time: 15:31
 */

/**
 * Affiche les amis de l'utilisateur concerné sous forme de grille (test)
 * IDuser en GET
 */

// API de gestion des amis
include_once('../api/model/friend/listFriendsAPI.php');

// ID API
define('API_ID', '47856230');

// Check paramètres GET
if (!isset($_GET['IDuser'])) {
    $_GET['IDuser'] = '251f563068e8636da4092490d6aeac94'; // TODO debug François
}

// Sécurisation
$user_id = strip_tags($_GET['IDuser']);

// Récupération des données de l'API
$dataAPI = listFriendsAPI(API_ID, $user_id);
$dataFriends = $dataAPI['data'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="../images/ic_launcher.png"/>
    <link type="text/css" rel="stylesheet" href="css_friends.css"/>
    <title>WDIDY - Friends</title>
</head>
<body>

<!-- colonnes : photo --- nom/prénom ... -->
<table>
    <?php

    // On boucle sur chacun des amis
    foreach ($dataFriends as $friend) {

        $imgLink = '../picts/' . $friend['IDfriend'] . '.jpg'; // Lien de la photo de profil
        if (!file_exists($imgLink)) {
            $imgLink = '../picts/guy.jpg';
        }
        $name = $friend['firstname'] . ' ' . $friend['lastname']; // Nom et prénom

        ?>

        <tr>
            <td>
                <img src="<?php echo $imgLink; ?>" class="friend_thumb">
            </td>
            <td>
                <span class="friend_text"><?php echo $name; ?></span>
            </td>
        </tr>

        <?php
    }
    ?>

</table>

</body>
</html>