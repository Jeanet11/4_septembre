	<?php
//Fonction de test de champ vide
	function testEmptyField($field) {
		if(empty($_POST[$field])) {
			return "Le champ ".$field." est vide.<br>";
		}
	}


// verifie que le tableau $_POST n'est pas vide
	if(!empty($_POST)) {

// validation du formulaire
		$message='';
		$erreur='';
// récupère les input dans des variables sécurisées
		$prenom = htmlspecialchars($_POST['prenom']);
		$nom = htmlspecialchars($_POST['nom']);
		$age = htmlspecialchars($_POST['age']);
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$password1 = htmlspecialchars($_POST['password1']);
		$password2 = htmlspecialchars($_POST['password2']);
		$email = htmlspecialchars($_POST['email']);
		$password = password_hash($password1, PASSWORD_BCRYPT);


// si la longueur du mot de passe est inférieure à 8, message d'erreur
		if(strlen($password1)<8) {
			$erreur .=  "La longueur du mot de passe doit être égale ou supérieure à 8<br>";
		}
// verifie que les mots de passe sont identiques		
		
		if ($password1 !== $password2) {
			$erreur .=  "Les mots de passe ne sont pas identiques<br>";
		}

		$erreur .= testEmptyField('prenom');
		$erreur .= testEmptyField('nom');
		$erreur .= testEmptyField('age');
		$erreur .= testEmptyField('pseudo');
		$erreur .= testEmptyField('email');


//essai de connexion à la bdd
		if(empty($erreur)) {
			try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=annonces-immo-dump;charset=utf8','root','admin');
			}
			catch (Exception $e)
			{
				die('Erreur : '.$e->getMessage());
			}

// préparation de la requete sql qui inserera les input dans la bdd
			$sql = sprintf("INSERT INTO uti_utilisateur(uti_oid, uti_prenom, uti_nom, uti_age,uti_pseudo, uti_password, uti_email) VALUES (null, '%s', '%s', %d, '%s', '%s', '%s')", $prenom, $nom, $age, $pseudo, $password, $email);
// execution de la requete

			if($bdd->exec($sql) === 1) {
				$message = "l'utilisateur a été inséré avec succès";
				// header("Location:pages/ajout_utilisateur.php");
			}
			else {
				$erreur .= "impossible d'insérer ces données : ".$bdd->errorInfo()[2];
			}
		}

//On affiche les informations d'erreur ou de succès
		if( !empty($erreur) ) {
			echo "<p class='text-danger'>" . $erreur . "</p>";
		} else {
			echo "<p class='text-success'>" . $message . "</p>";
		}
	}

	?>

	<!-- formulaire bootstrap-->
	<div class="container">


		<form method="POST" class="col-md-offset-4 col-md-4">

			<div class="form-group">
				<label for="prenom" class="col-sm-2 control-label">Prénom</label>

				<input type="text" class="form-control" id="prenom" name="prenom" placeholder="prenom" value="<?= isset($_POST['prenom']) ? $_POST['prenom'] : ''?>" >
			</div>


			<div class="form-group">
				<label for="nom" class="col-sm-2 control-label">Nom</label>

				<input type="text" class="form-control" name="nom" id="nom" value="<?= isset($_POST['nom']) ? $_POST['nom'] : ''?>" required />
			</div>


			<div class="form-group">
				<label for="age" class="col-sm-2 control-label">Age</label>

				<input type="number" class="form-control" name="age" id="age" value="<?= isset($_POST['age']) ? $_POST['age'] : ''?>" />
			</div>

			<div class="form-group">
				<label for="pseudo" class="col-sm-2 control-label">Pseudo</label>

				<input type="text" class="form-control" name="pseudo" id="pseudo" value="<?= isset($_POST['pseudo']) ? $_POST['pseudo'] : ''?>" required />
			</div>


			<div class="form-group">
				<label for="password1" class="col-md-8 control-label">Mot de passe</label>

				<input type="password" class="form-control" name="password1" id="password1" value="<?= isset($_POST['password1']) ? $_POST['password1'] : ''?>" required/>
			</div>

			<div class="form-group">
				<label for="password2" class="col-md-8 control-label">Confirmer le mot de passe</label>

				<input type="password" class="form-control" name="password2" id="password2" required/>
			</div>

			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">E-mail</label>

				<input type="email" class="form-control" name="email" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''?>" required pattern= preg_match("^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$", $email)/>
			</div>

			<button type="submit" class="btn btn-default" id="annuler">Annuler</button>
			<input type="submit" name="valider" class="btn btn-primary" value="valider" />
		</form>
	</div><!--fin div container-->


