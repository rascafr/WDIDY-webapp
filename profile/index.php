<?php
session_start();

// Protect access from history is not logged to WDIDY
if (!(isset($_SESSION['IDuser']) AND $_SESSION['IDuser'] != '')) {
    header('Location: ../index.php');
    exit();
}
?>

<!--<!DOCTYPE html>-->
<html>
<head>
    <title>WDIDY</title>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="../images/ic_launcher.png"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel="stylesheet" type="text/css" href="../dist/sweetalert.css"/>
    <link rel="stylesheet" type="text/css" href="slider/style.css"/>
    <link rel="stylesheet" type="text/css" href="loader/style.css"/>
    <link rel="stylesheet" type="text/css" href="../malihu-custom-scrollbar-plugin-3.1.1/jquery.mCustomScrollbar.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="../malihu-custom-scrollbar-plugin-3.1.1/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../dist/sweetalert.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
    <script type="text/javascript">

        function toggleMenu() {

            e = document.getElementById('menuProfil');

            if (e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                e.style.display = 'block';
            }
        }

        function toggleMsg() {

            e = document.getElementById('chatroom');

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
</head>

<body>

<?php
include("../include/sql.php");
if (isset($_GET['trackID'])) {
    $trackID = $_GET['trackID'];
} else {
    $trackID = 1; // default
}

// get first track point
$row = $bdd->prepare('SELECT * FROM `wdidy-point` WHERE `IDtrack` = ? LIMIT 0,1 ');
$row->execute(array($trackID));
$data = $row->fetch();
$row->closeCursor();

$lat = $data['lat'];
$lon = $data['lon'];

?>

<div id="loader-wrapper">
    <div id="loadertxt">WDIDY</div>
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            $('body').addClass('loaded');
            $('h1').css('color', '#222222');
        }, 1000);

    });
</script>

<!-- insert google map map's inside website-->
<script type="text/javascript">
    var markers = [];
    var infowindow = null;
    var map;
    var Path;

    function initialize() {
        var myStyles = [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [
                    {visibility: "off"}
                ]
            }
        ];

        mapTypeControl: true;
        var mapCanvas = document.getElementById("map");
        var mapOptions = {
            //center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lon; ?>),
            //zoom: 17,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: myStyles
        };
        map = new google.maps.Map(mapCanvas, mapOptions);
        var image = "custom-icons/marker.png";
        var i = 0;
        // InfoWindow content
        var content = '<div id="iw-container">' +
            '<div class="iw-title">52 rue boisnet 49100 Angers</div>' +
            '<div class="iw-content">' +
            '<div class = "photos" style="background-image: url(../picts/drunk-guy.jpg);" onClick = "show()"></div> ' +
            '<div class = "photos" style="background-image: url(../picts/1.jpg);"></div> ' +
            '<div class = "photos" style="background-image: url(../picts/2.jpg);"></div> ' +
            '<div class = "photos" style="background-image: url(../picts/guy.jpg);"></div> ' +
            '</div>' +
            '</div>';

        var userPath = [

            <?php
            $req = $bdd->prepare('SELECT pt.lat, pt.lon, track.name, user.firstname, user.lastname, user.IDuser, track.start FROM `wdidy-point` pt,`wdidy-track` track,`wdidy-user` user
                                                   WHERE user.IDuser = ?
                                                   AND track.IDtrack = ?
                                                   AND track.IDuser = user.IDuser
                                                   AND pt.IDtrack = track.IDtrack
                                                   ');
            $req->execute(array($userGetID, $trackID));
            while ($result = $req->fetch(PDO::FETCH_ASSOC)) {
                echo '{lat: '.$result['lat'].', lng: '.$result['lon'].'},';
                $userName = $result['firstname'].' '.$result['lastname'];
                $title = $result['name'];
                $userID = $result['IDuser'];

                $date =  $result['start'];

                // Check if user has picture on server
                if (!file_exists('../picts/'.$userGetID.'.jpg')) {
                    // Replace it by default picture
                    $userPict = "'../picts/empty.jpg'";
                } else {
                    // Path with ' character for html - css implementetion (see below)
                    $userPict = "'../picts/guy.jpg'";
                }
            }

            ?>
        ];

        // Create bound
        var bounds = new google.maps.LatLngBounds();

        while (i < userPath.length) {

            bounds.extend(new google.maps.LatLng(userPath[i]['lat'], userPath[i]['lng']));

            var marker = new google.maps.Marker({
                position: userPath[i],
                map: map,
                title: "hello",
                icon: image
            });


            infowindow = new google.maps.InfoWindow({
                content: content
            });

            google.maps.event.addDomListener(map, "click", function () {
                infowindow.close(map, this);
                maxWidth: 300
            });

            google.maps.event.addListener(marker, "click", function () {
                map.panTo(this.getPosition());
                map.panBy(0, -200);
                infowindow.open(map, this);
                map.setZoom(16);
            });

            google.maps.event.addListener(infowindow, 'domready', function () {

                // Reference to the DIV that wraps the bottom of infowindow
                var iwOuter = $('.gm-style-iw');

                var iwBackground = iwOuter.prev();

                // Removes background shadow DIV
                iwBackground.children(':nth-child(2)').css({'display': 'none'});

                // Removes white background DIV
                iwBackground.children(':nth-child(4)').css({'display': 'none'});

                // Moves the infowindow 115px to the right.
                iwOuter.parent().parent().css({left: '235px', top: '20px'});

                // Moves the shadow of the arrow 76px to the left margin.
                iwBackground.children(':nth-child(1)').css({'display': 'none'});

                // Moves the arrow 76px to the left margin.
                iwBackground.children(':nth-child(3)').css({'display': 'none'});

                // Reference to the div that groups the close button elements.
                var iwCloseBtn = iwOuter.next();

                // Apply the desired effect to the close button
                iwCloseBtn.css({opacity: '1', right: '53px', top: '1px', border: '7px solid #EC8074', 'border-radius': '13px'});

                // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
                iwCloseBtn.mouseout(function () {
                    $(this).css({opacity: '1'});
                });
            });

            marker.setMap(map);
            markers.push(marker);

            i++;
        }

        // Fit bounds
        map.fitBounds(bounds);

        Path = new google.maps.Polyline({
            Path: userPath,
            geodesic: true,
            strokeColor: "#E74C3C",
            strokeOpacity: 1.0,
            strokeWeight: 2
        });
        Path.setMap(map);
    }
    google.maps.event.addDomListener(window, "load", initialize);

    function hideMarkers() {
        if ($("#markers").is(":checked")) {
            setMapOnAll(null);
        } else {
            setMapOnAll(map);
        }
    }

    function hidePath() {
        if ($("#track").is(":checked")) {
            Path.setMap(null);
        } else {
            Path.setMap(map);
        }
    }

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    var img = new Image();
    var hi;
    var wi;
    var ratio;

    //cant access width and height parameters until img has loaded :(...
    //TODO: correct bug that shows image once it has been displayed twice..
    img.onload = function () {
        wi = this.width;
        hi = this.height;
        ratio = ((hi) / (wi)); // get the image ratio in order to resize image properly to fit browser
    };

    img.src = '../picts/timed.jpg';

    function show() {
        $('#disp').css('background-repeat', 'no-repeat');
        $('#disp').css('display', 'inline');
        $('#trackimg').css('background-image', 'url(../picts/drunk-guy.jpg)');
        $('#trackimg').css('background-repeat', 'no-repeat');

        if (ratio < 1) {
            $('#trackimg').css('height', (document.getElementById('trackimg').clientWidth * ratio));
            $('#trackimg').css('width', '50%');
        } else {
            $('#trackimg').css('width', (document.getElementById('trackimg').clientHeight / ratio));
            $('#trackimg').css('height', '50%');
        }
    }

    function endShow(divid) {
        $('#disp').css('display', 'none');
    }

    window.addEventListener('resize', function (event) {
        if (ratio < 1) {
            $('#trackimg').css('height', (document.getElementById('trackimg').clientWidth * ratio));
            $('#trackimg').css('width', '50%');
        } else {
            $('#trackimg').css('width', (document.getElementById('trackimg').clientHeight / ratio));
            $('#trackimg').css('height', '50%');
        }
    });

    function del() {

        swal({
            title: "Delete picture?",
            text: "your picture will constantly be removed of your timeline",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: false
        });

    }

</script>

<div id="wrapper">

    <div id="map"></div>
    <div class="border-right"></div>
    <div class="border-line"></div>

    <div id="disp" onclick="endShow(this)">
        <div id="trackimg">
            <div class="menu-im" onclick="del()">
                <b>x</b> Delete image
            </div>
            <div class="menu-close" onclick="endShow(this)">
                <b>x</b>
            </div>
        </div>
    </div>

    <?php
    include("../header-log.php");
    setlocale(LC_TIME, 'fr_FR');
    $date = utf8_encode(strftime('%A %e %B %Y', strtotime($date)));
    ?>

    <!--User profile configuration-->
    <div class="user">
        <?php echo($userName); ?>
    </div>

    <div class="mCustomScrollbar" id="legende">
        <?php
        /** Track resume display */

        $row = $bdd->prepare('SELECT COUNT(*) AS NBLINES FROM `wdidy-point` WHERE `IDtrack` = ?');
        $row->execute(array($trackID));
        $data = $row->fetch();
        $row->closeCursor();

        $max = $data['NBLINES'];

        $row = $bdd->prepare('SELECT * FROM `wdidy-point` WHERE `IDtrack` = ?');
        $row->execute(array($trackID));

        $i = 0;
        $strLines = '';

        ?>

        <br>

        <?php

        foreach ($row as $rec) {

            $flag = 1;

            $add = $rec['address'];
            $adr = explode(',', $add);
            $fst = $adr[0];
            if (strlen($fst) > 17) {
                $fst = substr($adr[0], 0, 17);
                $fst .= '...';
            }

            $strLines .= '<div class="resume_address">';
            $strLines .= '<div class="resume_point"></div><div class="resume_addressText">' . $fst . '<br><span class="address">'.$adr[1].'</span>' . '</div>';
            $strLines .= '</div>';

            $strLines .= '<div class="resume_detail">';
            $strLines .= '<div class="resume_linkLine" ';
            if ($i == $max - 1) {
                $strLines .= 'style="opacity:0;"';
            }
            $strLines .= '></div>';
            //$strLines .= '<div class="resume_detailText">' . $rec['lat'] . '<br>' . $rec['lon'] . '</div>';
            $strLines .= '</div>';

            $i++;
        }

        echo $strLines;

        $row->closeCursor();
        ?>

    </div>

    <script>
        $("#legende").mCustomScrollbar({
            scrollInertia: 500,
            scrollbarPosition: "inline",
            contentTouchScroll: true
        });

    </script>


    <div class='title'>
        →
        <?php echo($title); ?>
    </div>

    <div class="border-txt">
        <div id="tit">My timeline:</div>
        <div class="txt">
            &laquo;
            <?php echo($title); ?>
            &raquo;<br>
            <?php echo($date); ?>
            <br>
        </div>
    </div>

    <div class="crop" style="background-image: url( <?php echo '../picts/'.$userGetID.'.jpg'; ?> );" onClick="toggleMenu();"></div>
    <div class="pref"></div>

    <?php include("slider/slider.php") ?>

    <div id="chatroom">
    	<div id="room"></div>
		<input class="mbox" id="login" type="text" name="msg" placeholder=" Ecrivez ici" />
    </div>

    <!--div id="menuProfil" style="position: absolute; display:none; top: 70px; right: 40px; width:200px; height:355px; background:#fff; box-shadow: 0px 4px 4px #888888;border-radius: 5px;"-->
    <div id="menuProfil">
        <ul>
            <a href="user-profile.php">
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
</div>
</body>
</html>