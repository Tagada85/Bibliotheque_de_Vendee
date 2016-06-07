<?php
    include('inc/header.php');
    require 'inc/calendrier.class.php';
    $evenements = new Calendrier;
    $calendrier = $evenements->recupererEvenementsCalendrier();
    if(!empty($_POST['titre']) && !empty($_POST['dateDebut']) && !empty($_POST['descriptif'])){
    	$titre = $_POST['titre'];
    	$dateDebut = $_POST['dateDebut'];
    	$dateFin = $_POST['dateFin'];
    	$descriptif = $_POST["descriptif"];
    	$image = $_POST['image'];
    	var_dump($dateFin);
    	var_dump($image);
    	$ajoutEvenement = $evenements->ajouterNouvelEvenement($titre, $dateDebut, $dateFin, $descriptif, $image);
    	echo $ajoutEvenement;
    	header("Refresh: 1");
    }

 ?>

 <section class="calendrier">
     <p>
         Voici les prochains événements concernant notre bibliothèque!
     </p>
     <?php
     	foreach($calendrier as $cal){
     		echo "<div class='evenement'><h2>" . $cal["Titre"] . "</h2>";
     		echo "<p>Image :" . $cal['Image'] . "</p>";
     		echo "<p>Date Debut : " . $cal['DateDebut'] . "</p>";
     		echo "<p>Descriptif: " . $cal['Descriptif'] . "</p>";
     		echo "<form action='#' method='POST'>";
     		echo "<input type='hidden' name='id_Evenement'>";
     		echo "<input type='submit' value='Supprimer'> ";
     	}
     ?>

 </section>
 <?php 
 if($_SESSION['Type'] == 'Employe'){
?>
	<form action="#" method="POST">
		<label for="titre">Titre de l'évènement: </label>
		<input type="text" name="titre" id="titre">
		<label for="dateDebut">Début de l'évènement: </label>
		<input type="date" name="dateDebut" id="dateDebut">
		<label for="dateFin">Date de fin de l'évènement: (si nécessaire)</label>
		<input type="date" name="dateFin" id="dateFin">
		<label for="image">Image: (optionnel)</label>
		<span>Renseignez uniquement le nom de l'image, celle-ci DOIT être présente dans le dossier img/ du serveur.</span>
		<input type="text" name="image" id="image">
		<textarea name="descriptif" placeholder="Entrez une description de l'évènement"></textarea>
		<input type="submit" value="Ajouter Evènement">
	</form>
 <?php
 }
 ?>


 <?php
 include('inc/footer.php');
  ?>
