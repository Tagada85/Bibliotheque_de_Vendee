<?php
    include('inc/header.php');
    require('inc/bibliotheque.class.php');
    $rechercheLivre = new Bibliotheque;
    $livreRechercher = $rechercheLivre->recupererLivreReservation($_GET['isbn']);
 ?>

<p>
    Vous désirez le livre suivant :
    <?php
        echo $livreRechercher[0]['Titre'];
        echo '<br />';
        echo $livreRechercher[0]['Auteur'];
        echo "<br />";
        echo $livreRechercher[0]["Editeur"];
     ?>
</p>


 <?php
     include('inc/footer.php');
  ?>
