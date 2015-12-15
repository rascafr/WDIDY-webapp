	<?php

	include('include/sql.php');

	// Récupération des variables nécessaires à l'activation
	/*$req = $bdd->prepare('SELECT * FROM  `app-userlist` WHERE (`email` = :email)');
	$req->execute(array('email' => $_GET['email']));*/

	$req = $bdd->prepare('SELECT * FROM  `app-userlist` WHERE 1');
	$req->execute();
	while ($result = $req->fetch(PDO::FETCH_ASSOC)) {
		if($result['email'] == $_GET['log']){
			$foundlog = 1;
			echo("ok");
		}
	}


	// Récupération des variables nécessaires à l'activation
	$req = $bdd->prepare('SELECT * FROM  `app-userlist` WHERE 7');
	$req->execute();
	while ($result = $req->fetch(PDO::FETCH_ASSOC)) {
		if($result['key'] == $_GET['key']){
			$foundkey = 1;
			echo("ok");
		}
	}

//echo $foundlog.<br>.$foundkey;

	if($foundlog){
		// On teste la valeur de la variable $actif récupéré dans la BDD
		if($active) // Si le compte est déjà actif on prévient
		  {
		     echo "Votre compte est déjà actif !";
		  }
		else // Si ce n'est pas le cas on passe aux comparaisons
		  {
		     if($foundkey) // On compare nos deux clés	
		       {
		          // Si elles correspondent on active le compte !	
		          echo "Votre compte a bien été activé !";
		 
		       }
		     else // Si les deux clés sont différentes on provoque une erreur...
		       {
		          echo "Erreur ! Votre compte ne peut être activé...";
		       }
		  }

	}else{
		echo"you are not in the database :/";
	}




?>