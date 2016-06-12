<?php
include 'inc/header.php';
require 'inc/bibliotheque.class.php';
$biblio = new Bibliotheque;
$emprunts = $biblio->recupererToutLesEmprunts();
if(!empty($_POST["isbn"])){
	$retour_emprunt = $biblio->validerRetourEmprunt($_POST['isbn']);
	echo $retour_emprunt;
	$dateRetour = new DateTime("now");
	$dateFinEmprunt = date_create($_POST['finEmprunt']);
	$diff = date_diff($dateFinEmprunt, $dateRetour);
	$joursRetard = $diff->days;
	if($joursRetard > 0 && $dateRetour > $dateFinEmprunt){
		$u = new Utilisateur;
		$ajoutPenalites = $u->ajoutPenalites($_POST['id_emp'], $joursRetard);
	}
	header("Refresh:0");
}
if(!empty($_POST['livre_emprunte']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['debut_emprunt'])){

	if(strlen($_POST["livre_emprunte"]) != 13){
		echo "L'isbn doit être composé de 13 chiffres.";
		return;
	}
	$user = new Utilisateur;
	$id = $user->recupererIdUtilisateur($_POST['nom'], $_POST['prenom']);
	$nouvelEmprunt = $biblio->ajouterEmprunt($_POST['livre_emprunte'], $id, $_POST['debut_emprunt']);
	echo $nouvelEmprunt;
}

?>

<section>
	<h3>Liste des emprunts en cours: </h3>
	<a href="#" id="toggle">Ajouter un livre à la liste d'emprunts</a>
	<div id="ajout_emprunt_form">
		<form action="#" method="POST">
			<label for="isbn">ISBN: </label>
			<input type="text" name="livre_emprunte" id="isbn">
			<label for="prenom">Prénom Emprunteur: </label>
			<input type="text" name="prenom" id="prenom">
			<label for="nom">Nom Emprunteur: </label>
			<input type="text" name="nom" id="nom">
			<label for="debut_emprunt">Début Emprunt: </label>
			<input type="date" name="debut_emprunt" id="debut_emprunt">
			<input type="submit" value="Ajouter Emprunt">
		</form>
	</div>
	<table class="liste_emprunts">
	<tr>
		<th>Titre</th>
		<th>Nom</td>
		<th>Prénom</th>
		<th>Début Emprunt</th>
		<th>Fin Emprunt</th>
	</tr>
	<?php 
		foreach($emprunts as $emp){
			echo "<tr><td> " . $emp["Titre"] . "</td>";
			echo "<td> " . $emp['Nom'] . "</td>";
			echo "<td>" . $emp['Prenom'] . "</td>";
			echo " <td>" . $emp["Debut_Emprunt"] . "</td>";
			echo " <td>" . $emp["Fin_Emprunt"] . "</td>";
			echo "<td>
				<form action='#' method='POST'>
					<input type='hidden'
					name='isbn' value='".$emp['ISBN'] ."'>
					<input type='hidden' name='id_emp' value=' " . $emp['ID'] . " '>
					<input type='hidden' name='finEmprunt' value=' " . $emp['Fin_Emprunt'] . " '>
					<input type='submit' class='emprunt_retour'
					id='retour_btn' value='Livre retourné'>
				</form>
			</td></tr>";
		}
	?>
	</table>
</section>
<script>
	var retour = document.getElementsByClassName('emprunt_retour');	
	for(var i = 0; i < retour.length; i++){
		retour[i].onclick = function(e){
			var livreRetour = confirm("Ce livre a été retourné aujourd'hui?");
			if(!livreRetour){
				e.preventDefault();
			}
		}
	}
	var empruntForm = document.getElementById("ajout_emprunt_form");
	var toggleForm = document.getElementById('toggle');
	empruntForm.style.display = 'none';
	toggleForm.onclick = function(){
		if(empruntForm.style.display == 'none'){
			empruntForm.style.display = 'block';
		}else{
			empruntForm.style.display = "none";
		}
	}
</script>

<?php
include 'inc/footer.php';
?>