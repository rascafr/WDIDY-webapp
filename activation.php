<?php
/**
 * Permet de gérer l'activation d'un profil utilisateur depuis un lien reçu par mail.
 * L'utilisateur reçoit un mail avec en GET :
 * - uid : l'identifiant utilisateur précédemment créé (md5)
 */

// Check session
session_start();

// 3h
define('MAX_REG_TIME', 3*3600);

// Code de retour
$returnCode = 0;

// En plus
$userName = '';

// Si utilisateur déjà connecté → présent par erreur d'historique, on le redirige vers l'index
// Idem si il n'y a pas de paramètres GET
if ((isset($_SESSION['IDuser']) AND $_SESSION['IDuser'] != '') OR !isset($_GET['uid'])) {
    header('Location: index.php');
}

// Sinon, on check les paramètres GET
else {

    // Accès BDD
    include('include/sql.php');

    // Sécurisation rapide
    $user_id = strip_tags($_GET['uid']);

    // Récupération de la date
    date_default_timezone_set('Europe/Paris');
    $date = new DateTime();
    $timestamp = $date->getTimestamp();
    $strDate = date('Y-m-d H:i:s', $timestamp);

    // Récupération du compte utilisateur
    $req=$bdd->prepare('SELECT *, TIMESTAMPDIFF(SECOND, `logday`, ?) AS TSTP FROM `wdidy-user` WHERE IDuser = ?');
    $req->execute(array($strDate, $user_id));
    $data = $req->fetch();
    $req->closeCursor();

    // Le compte n'existe pas ? Où est déjà activé ? On redirige vers l'index directement
    // TODO peut être mettre un message si mauvais ID car il y a toujours des trous du cul qui savent pas recopier une URL ...
    if (count($data['IDuser']) == 0 OR $data['active'] == 1) {
        header('Location: index.php');
        exit();
    } else if ($data['TSTP'] > MAX_REG_TIME) { // Le compte existe, on check si ça fait moins de 3h qu'il y a eu la demande
        // Trop de temps écoulé, suppression ligne et message d'erreur associé
        $req=$bdd->prepare('DELETE FROM `wdidy-user` WHERE IDuser = ?');
        $req->execute(array($user_id));
        $req->closeCursor();
        $returnCode = -1;
    } else {
        // Tout semble ok, mise à jour de la BDD et message de confirmation
        $req=$bdd->prepare('UPDATE `wdidy-user` SET `active` = 1 WHERE IDuser = ?');
        $req->execute(array($user_id));
        $req->closeCursor();
        $returnCode = -2;
    }

    // Pas concerné par le exit() donc OKLM
    $userName = $data['firstname'];
}

?>

<!DOCTYPE html>
<html lang="fr" id="wdidy">
<head>
    <title>WDIDY</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="background.css"/>
    <link rel="icon" type="image/png" href="images/ic_launcher.png"/>
    <script src="dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">
    <meta charset="utf-8"/>
    <!-- Last version of Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>

<body>
<div id="wrapper">

<?php

if ($returnCode == -1) {
    ?>

    <script> swal({
                title: "Oups",
                text: "Too much time has passed since your registration, <?php echo $userName; ?>.\nPlease recreate your account.",
                type: "error"
            },
            function () {
                window.location.href = 'index.php';
            });
    </script>

    <?php
} else if ($returnCode == -2) {
    ?>

    <script> swal({
                title: "Hey !",
                text: "Glad to see you, <?php echo $userName; ?>.\nYour account has been activated.\nEnjoy your parties !",
                type: "success"
            },
            function () {
                window.location.href = 'index.php';
            });
    </script>

    <?php
}

include("header-log.php");
include("log.php");
include("body-log.php");

?>

</div>

</body>
</html>
