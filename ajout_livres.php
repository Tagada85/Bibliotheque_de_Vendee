<?php
include 'inc/header.php';
include 'inc/bibliotheque.class.php';
$isbn = $_POST['isbn'];
$titre = $_POST['titre'];
$auteur = $_POST["auteur"];
$annee = $_POST["annee"];
$editeur = $_POST['editeur'];
$genre = (int)$_POST["genre"];
$langue = (int)$_POST["langue"];
$image = $_POST["image"];

$infosBiblio = new Bibliotheque;
$genres = $infosBiblio->recupererGenres();
$langues = $infosBiblio->recupererLangues();

if(!empty($isbn) && !empty($titre) && !empty($auteur) && !empty($genre) && !empty($editeur) && !empty($langue) && !empty($image) && !empty($annee)){

	$chiffres = array('0', '1', '2', "3", '4', '5', '6', '7', '8', '9');
	$lettresISBN = str_replace($chiffres, '', $isbn);
	
	if(strlen($isbn) != 13 || strlen($lettresISBN) !== 0){
		echo "L'ISBN n'est pas valide. Il doit être composé de 13 chiffres.";

	}else{
		$url_image = 'img/' . $image;
		$nouveauLivre = new Bibliotheque;
		$ajoutlivre = $nouveauLivre->ajouterLivre($isbn, $titre, $auteur, $editeur, $annee, $genre, $langue, $url_image);
		echo $ajoutlivre;
	}
	

}

?>

<h3>Afin d'ajouter un livre à la bibliothèque, veuillez compléter le formulaire.</h3>
<form method="POST" action="#">
	<label for="isbn">ISBN:</label>
	<input type="text" id="isbn" name="isbn">
	<label for="titre">Titre: </label>
	<input type="text" id="titre" name="titre" >
	<label for="auteur">Auteur(s)</label><br>
	<span>Si plusieurs, séparez-les par une virgule</span>
	<input type="text" id="auteur" name="auteur" ">
	<label for="annee">Année Publication: </label>
	<input type="text" id="annee" name="annee" >
	<label for="editeur">Editeur: </label>
	<input type="text" id="editeur" name="editeur"  >
	<label for="genre">Genre:</label>
	<select name="genre">
	<option value="#" disabled selected>Choisissez un genre</option>
		<?php 
			foreach($genres as $genre){
				echo "<option value='" .$genre["ID"] ."'>". $genre['ID'] . " - " .$genre['Libelle'];
			}
		?>
	</select><br>
	<label for="langue">Langue: </label>
	<select name="langue">
		<option value="#" disabled selected>Choisissez une langue</option>
		<?php 
			foreach($langues as $langue){
				echo "<option value='" .$langue["ID"] ."'>". $langue['ID'] . " - " .$langue['Libelle'];
			}
		?>
	</select><br>
	<label>Image Couverture:</label><br>
	<span>Renseignez uniquement le nom de l'image, celle-ci DOIT être présente dans le dossier img/ du serveur.</span>
	<input type="text" name="image">
	<input type="submit" value="Ajouter Livre">
</form>

<?php
include 'inc/footer.php';
?>