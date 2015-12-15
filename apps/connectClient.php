<?php

	/**
	 * Permet de valider la connexion d'un utilisateur auprès du serveur
	 * Paramètres POST :
	 * email : l'adresse email du client (encodé URL)
	 * password : le mot de passe hashé en sha256
	 * hash : le hash de email + password + salt
	 */

	$shaSalt = 'Erreur réseau';
	$mdpSalt = 'You forgot something !';

	// Accès BDD
	include ('../include/sql.php');

	/**
	 * Retourne : Si connexion depuis app (POST ok, hash ok)
	 *  1 : Connexion OK
	 * -2 : Mauvais mot de passe / adresse email
	 */
	
	$resultApp = -1;
	$resultHumain = -1;

	// Check des paramètres POST
	if (isset($_POST['email']) AND isset($_POST['password']) AND isset($_POST['hash'])) {



		$emailPost = $_POST['email'];
		$emailClient = urldecode($emailPost);
		$passwordClient = $_POST['password'];
		$hash = $_POST['hash'];

		// Check du hash
		if ($hash === hash('sha256', $emailPost.$passwordClient.$shaSalt)) {

			// App pass OK
			$resultHumain = 1;

			// Check mot de passe et adresse mail
			// SELECT * FROM `app-userlist` WHERE (`email` = ? AND `password` = ? AND `active` = 1)
			$req = $bdd->prepare('SELECT * FROM `wdidy-user` WHERE (`email` = ? AND `password` = ? AND `active` = 1)');
			$req->execute(array($emailClient, $passwordClient));
			$data = $req->fetch();	

			$resultApp = count($data) != 1 ? 1 : -2;

			$preData = array();
			$preData['IDuser'] = $data['IDuser'];
			$preData['firstname'] = $data['firstname'];
			$preData['lastname'] = $data['lastname'];
			$preData['country'] = $data['country'];
			$preData['city'] = $data['city'];

			// -> age : non car la chatte de la grand mère suffit à Timé
		}

	}

	// Check humain
	if ($resultHumain == -1) {
		include('error.php');
	} else {

		//echo $resultApp;
		$resData = array();
		$resData['result'] = $resultApp;
		$resData['data'] = $preData;
		echo json_encode($resData);

	}

?>