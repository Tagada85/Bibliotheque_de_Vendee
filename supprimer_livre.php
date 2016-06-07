<?php
include 'inc/header.php';
require 'inc/bibliotheque.class.php';
$isbn = $_GET["isbn"];
$infosLivre = new Bibliotheque;
$livreSuppression = $infosLivre->recupererInfosLivre($isbn);
if(isset($_POST['delete']) && $_POST['delete'] == "1"){
	$supprimerLivre = $infosLivre->supprimerLivre($isbn);
	echo $supprimerLivre;
}

?>

<section>
	<?php if($_POST['delete'] != "1"){ ?>
		<p>Vous êtes sur le point de supprimer le livre suivant: </p>
		<p>Titre: <?php echo $livreSuppression['Titre']; ?></p>
		<p>Auteur: <?php echo $livreSuppression['Auteur']; ?></p>
		<p>Editeur: <?php echo $livreSuppression['Editeur'] ?></p>
		<p>Etes vous sur de vouloir continuer? </p>
		<form method="POST" action="#">
			<input type="hidden" name="delete" value="1">
			<input type="submit" value="Supprimer ce livre">
		</form>
		<?php
	}else{
		echo "<a href='livres.php'>Retour à la bibliothèque</a>";
	}
	?>
</section>





<?php
include 'inc/footer.php';
?>