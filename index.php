<?php
    // Initialisation session
    session_start();
?>
<html>
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

    <!--body-->

    <?php

    $isConnect = -1; // par défaut on suppose que la connexion n'a pas eu lieu
    include("include/sql.php");

    // Session - déjà connecté
    if (isset($_SESSION['IDuser']) AND $_SESSION['IDuser'] != '') {
        $isConnect = 1;
    }

    // Action si infos de log OK
    else if (isset($_POST['email']) AND isset($_POST['password'])) {

        $req = $bdd->prepare('SELECT * FROM `wdidy-user` WHERE (`email` = ? AND `password` = ? AND `active` = 1)');
        $req->execute(array(urldecode($_POST['email']), hash('sha256', $_POST['password'].'You forgot something !')));
        $data = $req->fetch();
        $req->closeCursor();
        if (count($data) != 1) {
            $isConnect = 1; // Success

            // Save data into session
            $_SESSION['IDuser'] = $data['IDuser'];
            $_SESSION['nameUser'] = $data['firstname'].' '.$data['lastname'];

        } else {
            $isConnect = 0; // Fail bad pass / email
        }

    // Action si demande d'inscription
    } else if (isset($_POST['name']) AND isset($_POST['lastname']) AND isset($_POST['mail']) AND isset($_POST['country']) AND isset($_POST['city']) AND isset($_POST['nmdp'])) {

        $req = $bdd->prepare('SELECT COUNT(*) AS CNT FROM  `wdidy-user` WHERE `email` = ?');
        $req->execute(array($_POST['mail']));
        $rs = $req->fetchAll();
        $req->closeCursor();

        if ($rs['CNT'] != 0) {
            $isExist = 1;
        } else {
            $isExist = 0;
        }

        if ($isExist == 1) {
            echo "<script> swal({
										title:'Oops...',
										text:'It seems like you are alreday in our database !',
										type:'error'
										},
										function(){
											window.location.href = 'index.php';
										});
							 </script>";
        } else if (empty($_POST['name']) || empty($_POST['mail']) || empty($_POST['lastname']) || empty($_POST['confirm']) || empty($_POST['country']) || empty($_POST['city']) || empty($_POST['nmdp'])) {
            echo "<script> swal({
										title:'Oops...',
										text:'It seems like you didn\'t fill the form correctly :(',
										type:'error'
										},
										function(){
											var table = ['name','lastname','mail','conf','country','city','nmdp'];
											var i=0;
											for(i=0;i<7;i++){
												if(!document.getElementById(table[i]).value){
													document.getElementById(table[i]).style.borderColor='#FF0000';
												}
											}
										});
							 </script>";
        } else if ($_POST['mail'] != $_POST['confirm']) {
            echo "<script> swal({
										title:'Oops...',
										text:'It seems like you didn\'t fill the form correctly :(',
										type:'error'
										},
										function(){
											var table = ['name','lastname','mail','conf','country','city','nmdp'];
											document.getElementById(table[3]).style.borderColor='#FF0000';
										});
							 </script>";
        } else {

            $email = trim($_POST['mail']);
            $name = trim($_POST['name']);
            $lastname = trim($_POST['lastname']);
            $country = trim($_POST['country']);
            $city = trim($_POST['city']);
            $salt = "You forgot something !";

            $password = hash('sha256', trim($_POST['nmdp']) . $salt);

            date_default_timezone_set('Europe/Paris');
            $script_tz = date_default_timezone_get();
            $date = new DateTime();
            $timestamp = $date->getTimestamp();


            $logday = date('Y-m-d H:i:s', $timestamp);

            $key = hash('md5', $timestamp . $email);

            $req = $bdd->prepare('INSERT INTO `wdidy-user`(IDuser,email,firstname,lastname,country,city,password,logday) VALUES(:IDuser,:email,:firstname,:lastname,:country,:city,:password,:logday)');
            $req->execute(array(
                'IDuser' => $key,
                'email' => $email,
                'firstname' => $name,
                'lastname' => $lastname,
                'country' => $country,
                'city' => $city,
                'password' => $password,
                'logday' => $logday
            ));

            // Préparation du mail contenant le lien d'activation
            $destinataire = $email;
            $sujet = "Activate your account";
            $entete = "From: basedonney@wdidy.com";

            // Le lien d'activation est composé du login(adresse mail) et de la clé(key)
            $message = "Welcome to WDIDY,
						 
						to activate your account, click on the link below or copy/paste the url in your favorite browser
						 
						http://217.199.187.59/francoisle.fr/wdidy/activation.php?log=" . urlencode($email) . "&key=" . urlencode($key) . "
						 
						 
						---------------
						This is an automatically generated email, please do not reply.";


            mail($destinataire, $sujet, $message, $entete); // Envoi du mail

            echo "<script> swal({
										title:'Welcome :) !',
										text:'You are going to receive an activation email',
										type:'success'
										},
										function(){
											window.location.href = 'index.php';
										});
							 </script>";

        }
    }

    // Header log si pas connecté (-1)
    if ($isConnect == -1) {
        include("header-log.php");
        include("log.php");
        include("body-log.php");
        include("commit_history.php");
    } // Header log et message d'erreur si mauvais identifiant
    else if ($isConnect == 0) {
        include("header-log.php");
        include("log.php");
        echo "<script> swal({
										title:'Bad login/password',
										text:'Please try again or create your own account',
										});
							 </script>";
        include("body-log.php");
        include("commit_history.php");
    } // Message connecté + contenu nécessaire si connecté
    else if ($isConnect == 1) {
        header('Location: profile/user-profile.php');
    }

    ?>

</div>
</body>

</html>             