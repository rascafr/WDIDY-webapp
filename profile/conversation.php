<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 20/12/2015
 * Time: 21:58
 */

session_start();

include('../include/sql.php');

/**
 * Permet d'afficher le profil de quelqu'un avec la conversation en cours
 * Paramètre GET :
 * - fid : L'identifiant de l'ami avec que l'utilisateur possède une conversation.
 */

// Check paramètres
// Protect access from history is not logged to WDIDY
if (!isset($_GET['fid']) AND (!(isset($_SESSION['IDuser']) AND $_SESSION['IDuser'] != ''))) {
    header('Location: ../index.php');
    exit();
}

// Definitions / access
$api_id = '47856230';

// Get Friend ID
$IDfriend = strip_tags($_GET['fid']);

// Get user ID
$IDuser = $_SESSION['IDuser'];

// Get user name
$userName = $_SESSION['nameUser'];

// Check if user has picture on server
if (!file_exists('../picts/' . $IDuser . '.jpg')) {
    // Replace it by default picture
    $userPict = "'../picts/empty.jpg'";
} else {
    // Path with ' character for html - css implementation (see below)
    $userPict = "../picts/" . $IDuser . ".jpg";
}

// Check if friend has picture on server
if (!file_exists('../picts/' . $IDfriend . '.jpg')) {
    // Replace it by default picture
    $friendPict = "'../picts/empty.jpg'";
} else {
    // Path with ' character for html - css implementation (see below)
    $friendPict = "../picts/" . $IDfriend . ".jpg";
}

// Get Friend name
$req=$bdd->prepare('SELECT * FROM `wdidy-user` WHERE IDuser = ?');
$req->execute(array($IDfriend));
$data = $req->fetchAll();
$req->closeCursor();

?>

<!DOCTYPE html>
<html>
<head>
    <title>WDIDY</title>
    <link rel="icon" type="image/png" href="../images/ic_launcher.png"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel="stylesheet" type="text/css" href="../dist/sweetalert.css">
    <link href='slider/style.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="loader/style.css"/>
    <link rel="stylesheet" type="text/css" href="../malihu-custom-scrollbar-plugin-3.1.1/jquery.mCustomScrollbar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="../malihu-custom-scrollbar-plugin-3.1.1/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
    <meta charset="utf-8"/>
</head>

<body>

<script type="text/javascript">

    function toggleMenu() {

        e = document.getElementById('menuProfil');

        if (e.style.display == 'block') {
            e.style.display = 'none';
        } else {
            e.style.display = 'block';
        }
    }

    (function ($) {
        $(window).load(function () {
            $(".content").mCustomScrollbar();
        });
    })(jQuery);

</script>

<script>

    var lastRet = '';

    // Send a message throught post.php interface
    function sendMessage() {

        var text = document.getElementById('in_msg').value;
        var textBase64 = btoa(document.getElementById('in_msg').value);

        // Si on a du texte, on lance la procédure d'envoi du message
        if (text.length > 0) {

            document.getElementById('in_msg').value = '';

            if (window.XMLHttpRequest) { xmlhttp = new XMLHttpRequest();} else { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
            xmlhttp.open("POST", "../api/request/message/post.php", true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send(
                "api_id="+encodeURIComponent('<?=$api_id?>')+"&"+
                "user_id="+encodeURIComponent('<?=$IDuser?>')+"&"+
                "friend_id="+encodeURIComponent('<?=$IDfriend?>')+"&"+
                "text="+encodeURIComponent(textBase64)
            );
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    // On récupère la réponse de l'interface PHP
                    var html = xmlhttp.responseText;

                    // On parse le JSON
                    var jsonObject = JSON.parse(html);

                    // Si pas d'erreur, on parse les résulats (sinon exception json-parser boum le navigateur)
                    if (jsonObject['error'] == 0) {
                        var messagesResult = jsonObject['data'];
                        document.getElementById('conv_main').innerHTML = parseJSONMessages(messagesResult);
                        document.getElementById("msg0").scrollIntoView(); // scroll to last message
                    } else {
                        alert('Erreur d\'envoi du message : ' + jsonObject['cause']);
                    }
                }
            };
        }
    }

    // Auto refresh conversation
    setInterval(function () {
        downloadJSONMessages();
    }, 1000);

    // Download data from single.php
    function downloadJSONMessages() {
        var html = '';
        if (window.XMLHttpRequest) { xmlhttp = new XMLHttpRequest();} else { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
        xmlhttp.open("POST", "../api/request/message/single.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send(
            "api_id="+encodeURIComponent('<?=$api_id?>')+"&"+
            "user_id="+encodeURIComponent('<?=$IDuser?>')+"&"+
            "friend_id="+encodeURIComponent('<?=$IDfriend?>')
        );
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                // On récupère la réponse de l'interface PHP
                var html = xmlhttp.responseText;

                if (html != lastRet) {
                    lastRet = html;

                    // On parse le JSON
                    var jsonObject = JSON.parse(html);

                    // Si pas d'erreur, on parse les résulats (sinon exception json-parser boum le navigateur)
                    if (jsonObject['error'] == 0) {
                        var messagesResult = jsonObject['data'];
                        document.getElementById('conv_main').innerHTML = parseJSONMessages(messagesResult);
                        document.getElementById("msg0").scrollIntoView(); // scroll to last message
                    } else {
                        alert('Erreur d\'envoi du message : ' + jsonObject['cause']);
                    }
                }
            }
        };

        return html;
    }

    // Function to parse JSON from single.php interface and display messages
    function parseJSONMessages(messagesResult) {

        var messagesHtml = '';

        for (var i=messagesResult.length-1;i>=0;i--) {
            var msgClass = '<?=$IDfriend?>' == messagesResult[i]['IDfriend'] ? 'message_sender' : 'message_friend';
            var imgClass = '<?=$IDfriend?>' == messagesResult[i]['IDfriend'] ? 'profile_small_user' : 'profile_small_friend';
            var talkPicture = '<?=$IDfriend?>' == messagesResult[i]['IDfriend'] ? '<?=$userPict?>' : '<?=$friendPict?>';
            messagesHtml += '<div class="message_container"><img class="' + imgClass + '" src="' + talkPicture + '"><div id="msg' + i + '" class="' + msgClass + '">' +  decode_utf8(atob(messagesResult[i]['text'])) + '</div></div>';
        }

        return messagesHtml;
    }

    function encode_utf8(s) {
        return unescape(encodeURIComponent(s));
    }

    function decode_utf8(s) {
        return decodeURIComponent(escape(s));
    }

</script>

<div class="thetitle">Your conversation</div>

<?php
include("../header-log.php");
setlocale(LC_TIME, 'fr_FR');
?>

<div class='title'>> Messages</div>

<div class="conversation_outside">

<div class="conversation_main" id="conv_main">

    <img src="ajax-loader.gif" class="progress_gif">

    <?php
/*
    // Search for all messages between user and his friend
    $req = $bdd->prepare("SELECT * FROM `wdidy-messages` WHERE
                          ((`IDsender` = ? AND `IDfriend` = ?) OR (`IDfriend` = ? AND `IDsender` = ?))
                          ORDER BY `date` ASC");
    $req->execute(array($IDuser, $IDfriend, $IDuser, $IDfriend));

    while ($result = $req->fetch(PDO::FETCH_ASSOC)) {

        $date = $result['date'];
        $IDmessage = $result['IDmessage'];
        $IDsender = $result['IDsender'];
        $text = base64_decode($result['text']);

        // Is user sender ?
        if ($IDsender == $IDuser) {
            $msgClass = 'message_sender';
            $pictClass = 'profile_small_user';
        } else {
            $msgClass = 'message_friend';
            $pictClass = 'profile_small_friend';
        }

        ?>
        <div class="message_container">
            <img class="<?php echo $pictClass; ?>" src="<?=($IDsender == $IDuser)?$userPict:$friendPict?>">
            <div id="msg<?php echo $IDmessage; ?>" class="<?php echo $msgClass; ?>">
                <?php echo $text; ?>
            </div>
        </div>

        <?php
    }
    $req->closeCursor();*/
    ?>

</div>

<div class="message_in_box">
    <input type="text" id="in_msg" class="message_input" placeholder="Composez votre message ...">
    <div class="message_button" id="bp_msg" onclick="sendMessage()"><img class="message_button_img" src="ic_send.png"></div>
</div>

</div>

<script>
    document.getElementById("msg<?php echo $IDmessage; ?>").scrollIntoView();
</script>

<!-- Profile Menu -->
<div id="menuProfil">
    <ul>
        <a href="index.php">
            <div class="case" id="top">Mon profil</div>
        </a>
        <div class="case" id="norm">Mes soirées</div>
        <div class="case" id="norm">Confidentialité</div>
        <div class="case" id="norm">Abonnement</div>
        <div class="case" id="norm">Aide</div>
        <a href="logout.php">
            <div class="case" id="bottom">Déconnexion</div>
        </a>
    </ul>
</div>

<!-- Profile picture -->
<div class="crop" style="background-image: url( <?php echo $userPict; ?> );" onClick="toggleMenu();"></div>

<!-- abeille -->
<div class="pref"></div>

<!-- Username from SESSION -->
<div class="user"><?php echo($userName); ?></div>

</body>

</html>
