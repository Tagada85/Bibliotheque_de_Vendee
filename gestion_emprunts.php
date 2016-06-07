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

?>

<section>
	<h3>Liste des emprunts en cours: </h3>
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
</script>

<?php
include 'inc/footer.php';
?>