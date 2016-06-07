<?php
    include('inc/header.php');
 ?>

<div>
    <?php if($_SESSION['Type'] == 'Visiteur'){
    echo 'Votre compte a été créer. Si vous désirez devenir membre, vous devez vous inscrire dans notre bibliothèque.
    En attendant, vous pouvez vous renseignez sur les livres disponibles.';
    }else if($_SESSION['Type'] == 'Membre'){
        echo "Votre compte a été créer, vous pouvez dès à présent réserver des livres via notre site et consulter l'état de vos emprunts.";
    }else if ($_SESSION['Type'] == 'Employe'){
        echo "Votre compte Employé a été créer. Vous pouvez dès à présent utiliser les fonctionnalités du site.";
        }?>

    <a href="/index.php" class="yellow_link">Accueil</a>
    <a href="/livres.php" class="yellow_link">Nos Livres</a>
</div>

 <?php
    include('inc/footer.php');
  ?>
