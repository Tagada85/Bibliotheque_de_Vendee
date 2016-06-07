<?php
include 'inc/header.php';
$ajoutMembre = new Utilisateur;
$typeInscription = $ajoutMembre->recupererTypesInscription();
if(!empty($_POST['type'])){
	$prenom = $_POST["prenom"];
	$nom = $_POST['nom'];
	$date_naissance = $_POST["date_naissance"];
	$adresse = $_POST["adresse"];
	$code_postal = $_POST["code_postal"];
	$ville = $_POST["ville"];
	$type = $_POST["type"];
	if(strlen($code_postal) != 5){
		echo "le code postal doit être composée de 5 chiffres.";
		echo "<a href='gestion_utilisateurs.php'>Retour au formulaire</a>"; 
		return;
	}
	$nouveauMembre = $ajoutMembre->creerNouvelUtilisateur($prenom, $nom,$date_naissance, $adresse, $code_postal, $ville, $type);
	echo $nouveauMembre;
}
?>

<section>
	<h2>Ajout membre ou employé</h2>
	<form action="#" method="POST">
		<label for="prenom">Prénom: </label>
		<input type="text" name="prenom" id="prenom" required>
		<label for="nom">Nom: </label>
		<input type="text" name="nom" id="nom" required>
		<label for="date_naissance">Date de Naissance: </label>
		<input type="date" name="date_naissance" id="date_naissance" required>
		<label for='adresse'>Adresse: </label>
		<input type="text" name="adresse" id="adresse" required>
		<label for="code_postal">Code Postal: </label>
		<input type="text" name="code_postal" id="code_postal" required>
		<label for="ville">Ville: </label>
		<input type="text" name="ville" id="ville" required>
		<label for="inscription">Type Inscription: </label>
		<select name="type">
		<option value="#" disabled selected>--Choisissez l'inscription--</option>
			<?php  
				foreach($typeInscription as $type){
					echo "<option value='" . $type['ID'] . "'>" . $type['Libelle'] . "</option>";
				}
			?>
		</select>
		<input type="submit" value="Ajout utilisateur">

	</form>
	
</section>


<?php
include 'inc/footer.php';
?>