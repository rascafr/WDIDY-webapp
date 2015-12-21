<!DOCTYPE <!DOCTYPE html>
<html>
	<head>
		<title>WDIDY</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" type="image/png" href="../images/ic_launcher.png" />
		<script src="dist/sweetalert.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../dist/sweetalert.css">
		<link rel="stylesheet" type="text/css" href="../dist/sweetalert.css">
		<meta charset="utf-8" />
		<!-- Last version of Jquery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery.jscrollpane.css" />
	    <!-- the jScrollPane script -->
	    <script type="text/javascript" src="jquery.jscrollpane.min.js"></script>
	 
	    <!--instantiate after some browser sniffing to rule out webkit browsers-->
	    <script type="text/javascript">
     
	    	function toggleMenu() {

	    		e = document.getElementById('menuProfil');

	    		if(e.style.display=='block'){
	    			e.style.display='none';
	    		} else {
	    			e.style.display='block';
	    		}
	    	}

      </script>
     
	</head>

	<?php 
		include("../include/sql.php");
		if (isset($_GET['trackID'])) {
			$trackID = $_GET['trackID'];
		} else {
			$trackID = 1; // default
		}

		if (isset($_GET['userID'])) {
			$userGetID = $_GET['userID'];
		} else {
			$userGetID = '251f563068e8636da4092490d6aeac94'; // default
		}
	?>	

		<body>
		<div id="wrapper">

		<!-- insert google map map's inside website-->

			<script src="https://maps.googleapis.com/maps/api/js?key="></script>

			<?php 

				echo '<script>
				function initialize() {
					mapTypeControl: true;
			        var mapCanvas = document.getElementById("map");
			        var mapOptions = {
			          center: new google.maps.LatLng(47.4747274, -0.5519100000000208),
			          zoom: 17,
			          mapTypeId: google.maps.MapTypeId.ROADMAP,
			        }
			        var map = new google.maps.Map(mapCanvas, mapOptions);
			        var image = "marker.png";
			        var i = 0;

					var userPath = [
					  ';

				$req = $bdd->prepare('SELECT pt.lat, pt.lon, track.name, user.firstname, user.lastname, user.IDuser, track.start FROM `wdidy-point` pt,`wdidy-track` track,`wdidy-user` user 
											   WHERE user.IDuser = ?
											   AND track.IDtrack = ?
											   AND track.IDuser = user.IDuser
											   AND pt.IDtrack = track.IDtrack
											   ');
				$req->execute(array($userGetID, $trackID));
				while ($result = $req->fetch(PDO::FETCH_ASSOC)) {
					echo '{lat: '.$result['lat'].', lng: '.$result['lon'].'},
					';
					$userName = $result['firstname'].' '.$result['lastname'];
					$title = $result['name'];
					$userID = $result['IDuser'];
					
					$date =  $result['start'];

					// Check if user has picture on server
					if (!file_exists('../picts/'.$userID.'.jpg')) {
						// Replace it by default picture
						$userPict = "'../picts/empty.jpg'";
					} else {
						// Path with ' character for html - css implementetion (see below)
						$userPict = "'../picts/".$userID.".jpg'";
					}
				}

			echo '];

					while(i < userPath.length){
							var marker = new google.maps.Marker({
					    	position: userPath[i],
					    	map: map,
					    	title: "hello",
					    	icon: image
						});
						marker.setMap(map);
						i++
					}
				  var Path = new google.maps.Polyline({
				    Path: userPath,
				    geodesic: true,
				    strokeColor: "#E74C3C",
				    strokeOpacity: 1.0,
				    strokeWeight: 2
				  });
				  Path.setMap(map);
				}
				google.maps.event.addDomListener(window, "load", initialize);
				</script>';

			?>


			<div id="map"></div>
			<div class = "border-right"></div>
			<div class = "border-line"></div>


			<?php 	
				include("../header-log.php");
				setlocale (LC_TIME, 'fr_FR'); 
      		  	$date = utf8_encode(strftime('%A %e %B %Y',strtotime($date)));	
			?>

			<!--User profile configuration-->

			<?php echo("<div class = 'user'>".$userName."</div>"); ?>			

			<div class ="legende">
			

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

			    foreach ($row as $rec) {

			    	$flag = 1;

			    	$strLines .= '<div class="resume_address">';
			    	$strLines .= '<div class="resume_point"></div><div class="resume_addressText">'.$rec['address'].'</div>';
			    	$strLines .= '</div>';

			    	$strLines .= '<div class="resume_detail">';
			    	$strLines .= '<div class="resume_linkLine" ';
			    	if ($i == $max - 1) {
			    		$strLines .= 'style="opacity:0;"';
			    	}
			    	$strLines .= '></div>';
			    	$strLines .= '<div class="resume_detailText">'.$rec['lat'].'<br>'.$rec['lon'].'</div>';
		    		$strLines .= '</div>';

		    		$i++;
				}

				echo $strLines;

				$row->closeCursor();
			?>

			</div>

			<?php echo("<div class = 'title'>> ".$title."</div>")?>

			<div class = "border-txt">

				<?php echo('<p>Ma timeline<p>
							<div class = "txt">&laquo '.$title.' &raquo<br>'.$date.'<br></div>'); ?>

			</div>

			<div class = "crop" style="background-image: url( <?php echo $userPict; ?> );" onClick="toggleMenu();"></div>
			<div class = "pref"></div>

			</div>

			<div id="menuProfil" style="position: absolute; display:none; top: 70px; right: 50px; width:15em; height:17em; background:#eee; box-shadow: 0px 4px 4px #888888;">
				
				<ul>

					<li>Mon profil</li>
					<li>Mes amis</li>
					<li>Paramètres</li>
					<li>Confidentialité</li>

				</ul>
			</div>

		</body>
	</html>