<!DOCTYPE html>
<html>
<head>
		<title>WDIDY</title>
		<link rel="icon" type="image/png" href="../images/ic_launcher.png" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" type="text/css" href="../dist/sweetalert.css">
		<link href='slider/style.css' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="loader/style.css" />
		<link rel="stylesheet" type="text/css" href="../malihu-custom-scrollbar-plugin-3.1.1/jquery.mCustomScrollbar.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="../malihu-custom-scrollbar-plugin-3.1.1/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="../dist/sweetalert.min.js"></script>
		<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
		<meta charset="utf-8" />
</head>

<body>

 <script type="text/javascript">
     
	    	function toggleMenu() {

	    		e = document.getElementById('menuProfil');

	    		if(e.style.display=='block'){
	    			e.style.display='none';
	    		} else {
	    			e.style.display='block';
	    		}
	    	}

	    	(function($){
        		$(window).load(function(){
            	$(".content").mCustomScrollbar();
      		  });
   		    })(jQuery);

</script>

	<div class = "border-right"></div>
	<div class = "border-line"></div>

	<?php 	
		include("../header-log.php");
		include("../include/sql.php");
		setlocale (LC_TIME, 'fr_FR'); 
		$date = utf8_encode(strftime('%A %e %B %Y',strtotime($date)));	
		echo("<div class = 'user'>".$userName."</div>");
	?>

	<div class = 'title'>> Profile</div>
	<div class = "trackcontainer">
	<?php

		$userGetID = '251f563068e8636da4092490d6aeac94';

				$req = $bdd->prepare('SELECT track.IDtrack, track.name, user.firstname, user.lastname, user.IDuser FROM `wdidy-track` track,`wdidy-user` user 
											   WHERE user.IDuser = ?
											   AND track.IDuser = user.IDuser
											   ');
				$req->execute(array($userGetID));
				while ($result = $req->fetch(PDO::FETCH_ASSOC)) {

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
						$userPict = "../picts/".$userID.".jpg";
					}

					echo('
						<div class = "tracklist" style = "background-image:url(\'http://maps.googleapis.com/maps/api/staticmap?center=Albany,+NY&zoom=7&scale=false&size=700x200&maptype=roadmap&format=png&visual_refresh=true\')">
								<div class = "tracktitle">
									<div class="tracktitledata">'
										.$result['name'].
									'</div>
									<div class = "button" onClick = "gototrack('.$result['IDtrack'].')">
										Show track
									</div>
								</div>
						<div class = "trackoptions">
							Track options
						</div>
						</div>
							');

				}
	?>
	</div>

	<div id="menuProfil" >
		<ul>

			<a href="http://217.199.187.59/francoisle.fr/wdidy/profile/user-profile.php">
				<div class = "case" id="top" >Mon profil</div>
			</a>
			<div class = "case" id="norm">Mes soirées</div>
			<div class = "case" id="norm">Confidentialité</div>
			<div class = "case" id="norm">Abonnement</div>
			<div class = "case" id="norm">Aide</div>
			<a href="http://217.199.187.59/francoisle.fr/wdidy/index.php">
				<div class = "case" id="bottom">Déconnexion</div>
			</a>
		</ul>
	</div>

	<script type="text/javascript">
		function gototrack(id){
			document.location.href="http://217.199.187.59/francoisle.fr/wdidy/profile/index.php?trackID=" + id;
		}
	</script>

	<div class = "crop" style="background-image: url( <?php echo $userPict; ?> );" onClick="toggleMenu();"></div>
	<div class = "pref"></div>
	
	<?php
		 echo("<div class = 'user'>".$userName."</div>");
	 ?>	


</body>

</html>