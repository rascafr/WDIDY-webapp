<?php
session_start();

// Protect access from history is not logged to WDIDY
if (!(isset($_SESSION['IDuser']) AND $_SESSION['IDuser'] != '')) {
    header('Location: ../index.php');
    exit();
}

// Definitions / access
$api_id = '47856230';
$user_id = $_SESSION['IDuser'];

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

<div class="border-right"></div>
<div class="border-line"></div>
<div class="thetitle">Your tracks</div>

<?php
include("../header-log.php");
include("../include/sql.php");
setlocale(LC_TIME, 'fr_FR');
$date = utf8_encode(strftime('%A %e %B %Y', strtotime($date)));
?>

<div class='title'>> Profile</div>

<!-- TODO absolute → relative -->
<div class="search_friend">
    <input type="text" class="search_input" id="in_search_friend" placeholder="Recherchez des personnes ..." oninput="startUserResearch()">
</div>
<div class="search_result" id="js_fields_friend_search" style="display: none;">
    &nbsp;
</div>

<script>
    // Lister for input text search event
    function startUserResearch() {

        var needle = document.getElementById('in_search_friend').value;

        // Si on a du texte, on lance la recherche
        if (needle.length > 0) {

            // On affiche la box
            document.getElementById('js_fields_friend_search').style.display="block";

            if (window.XMLHttpRequest) { xmlhttp = new XMLHttpRequest();} else { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
            xmlhttp.open("POST", "../api/request/friend/search.php", true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send(
                "api_id="+encodeURIComponent('<?=$api_id?>')+"&"+
                "user_id="+encodeURIComponent('<?=$user_id?>')+"&"+
                "needle="+encodeURIComponent(needle)
            );
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    // On récupère la réponse de l'interface PHP
                    var html = xmlhttp.responseText;

                    // On parse le JSON
                    var jsonObject = JSON.parse(html);

                    // Si pas d'erreur, on parse les résulats (sinon exception json-parser boum le navigateur)
                    if (jsonObject['error'] == 0) {
                        var searchResult = jsonObject['data'];
                        var searchHtml = '';

                        for (var i=0;i<searchResult.length;i++) {
                            searchHtml += '<a href="#' + searchResult[i]['IDuser'] + '"><div class="search_single"><img src="../picts/' + searchResult[i]['imgLink'] + '" class="search_image">' +
                                '<div class="search_text">' + searchResult[i]['firstname'] + ' ' + searchResult[i]['lastname'] + '</div></div></a>';
                        }

                        document.getElementById('js_fields_friend_search').innerHTML = searchHtml;
                    } else {
                        document.getElementById('js_fields_friend_search').innerHTML = 'Erreur de recherche : ' + jsonObject['cause'];
                    }
                }
            };
        } else {
            // Sinon, on n'affiche pas la box des résultats de recherche
            document.getElementById('js_fields_friend_search').style.display="none";
        }
    }
</script>

<div class="trackcontainer">

    <?php

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

    // Fetch track data from database
    $req = $bdd->prepare('SELECT track.IDtrack, track.name, user.firstname, user.lastname, user.IDuser
                          FROM `wdidy-track` track,`wdidy-user` user
						  WHERE user.IDuser = ? AND track.IDuser = user.IDuser');

    $req->execute(array($IDuser));
    while ($result = $req->fetch(PDO::FETCH_ASSOC)) {

        $trackTitle = $result['name'];
        $trackID = $result['IDtrack'];
        $date = $result['start'];

        ?>
        <div class="tracklist">
            <div class="tracktuname">
                <?php echo $userName; ?>
            </div>
            <div class="tracktitledata">
                <?php echo $trackTitle; ?>
            </div>
            <a href="trackview.php?trackID=<?php echo $trackID; ?>">
                <div class="button">
                    GO
                </div>
            </a>
            <div class="views">1578</div>
            <div class="shots">75</div>
            <div class="trackim"></div>
            <div
                class="stattrack"
                style="background-image:url(
                    'http://maps.googleapis.com/maps/api/staticmap?center=Albany,+NY&zoom=7&scale=false&size=700x200&maptype=roadmap&format=png&visual_refresh=true'
                );">
            </div>
        </div>
        <?php

    }
    ?>
</div>

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

<!-- Si clic sur track → redirection vers Maps -->
<!--<script type="text/javascript">
    function gototrack(id) {
        document.location.href = "index.php?trackID=" + id;
    }
</script>

<!-- Profile picture -->
<div class="crop" style="background-image: url( <?php echo $userPict; ?> );" onClick="toggleMenu();"></div>

<!-- abeille -->
<div class="pref"></div>

<!-- Username from SESSION -->
<div class="user"><?php echo($userName); ?></div>

</body>

</html>