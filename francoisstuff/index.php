<!DOCTYPE html>
<html>
	<head>
		<title>WDIDY</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" type="image/png" href="ic_launcher.png" />
		<meta charset="utf-8" />
	</head>

	<!-- PHP INCLUDES -->
	<?php include('include/sql.php'); ?>

	<body>

		<h2>WDIDY</h2>

		<div class="main">
			<p>404 Kadel Not Found</p>

			<? // test display database

				$req = $bdd->prepare('SELECT * FROM  `app-userlist` WHERE 1');
				$req->execute();
				while ($result = $req->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="material">'.$result['name'].'<br><span class="email">'.$result['email'].'</span></div><br>';
				}
				

			?>
		</div>

	</body>

</html>