<!--
style="float:left;width:382px;height:391px;

-->

<div class="formnpict">

	<div class="form">
		<form class="sign_in" action = "index.php" method = "post" autocomplete="off">
		<div class="title">Sign in</div>
		<div class="subtitle">Join the party society right now</div>
		<table>
			<tr>
				<td><input class="tb1" id="name" type="text" name="name" placeholder="Prénom" autocomplete="off" value="<?= $_POST['name'] ?>"></td>
				<td><input class="tb1" id="lastname" type="text" name="lastname" placeholder="Nom de famille" autocomplete="off" value="<?= $_POST['lastname'] ?>"></td>
			</tr>
			<tr>
				<td colspan="2"><input class="tb2" id="mail" type="email" name="mail" placeholder="Adresse e-mail" autocomplete="off" value="<?= $_POST['mail'] ?>"></td>
			</tr>
			<tr>
				<input style="display:none;" type="text" name="somefakename" />
				<input style="display:none;" type="password" name="anotherfakename" />
				<td colspan="2"><input class="tb2" id="conf" type="email" name="confirm" placeholder="Confirmation de l'adresse mail" autocomplete="off" value="<?= $_POST['confirm'] ?>"></td>
			</tr>
			<tr>
				<td colspan="2"><input class="tb2" id="country" type="text" name="country" placeholder="Pays" autocomplete="off" value="<?= $_POST['country'] ?>"></td>
			</tr>
			<tr>
				<td colspan="2"><input class="tb2" id="city" type="text" name="city" placeholder="Ville" autocomplete="off" value="<?= $_POST['city'] ?>"></td>
			</tr>
			<tr>
				<td colspan="2"><input class="tb2" id="nmdp" type="password" name="nmdp" placeholder="Nouveau mot de passe" autocomplete="off" value="<?= $_POST['nmdp'] ?>"></td>
			</tr>
		</table>
		<table class= "subscribe">
			<tr>
				<td><input class="signupnow" type="submit" value="Sign me in now! *"></td>
			</tr>
			<tr>
				<td><span class="default">*By clicking submit you are agreeing to the Terms and Conditions</span></td>
			</tr>
		</table>
		</form>
	</div>

	<img class = "phone" src ="images/phone.png" alt="phone.png"/>

</div>
