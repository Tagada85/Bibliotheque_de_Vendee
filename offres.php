<?php
    include('inc/header.php');
    require('inc/bibliotheque.class.php');
    $typeInscription = new Bibliotheque;
    $offres = $typeInscription->recupererOffresBibliotheque();
 ?>
<section class="offres">
    <h3>Vous pouvez choisir votre formule parmi les trois offres ci-dessous: </h3>
    <?php
        foreach($offres as $offre){
            echo '<div class="offre_container">';
            echo "<h2>Offre " .$offre['Libelle']. "</h2>";
            echo "<p>Nombre d'emprunts par mois :"   . $offre['NombreEmprunts'] . "</p>";
            echo 'Prix :' .$offre['PrixMensuel'] . " €/mois";
            echo '</div>';
        }
     ?>
</section>


 <?php
    include('inc/footer.php');
  ?>
