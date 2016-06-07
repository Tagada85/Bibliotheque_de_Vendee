<?php
    include('inc/header.php');
    require_once('inc/bibliotheque.class.php');
    $nomAuteur = $_GET["auteur"];
    $nomEditeur = $_GET["editeur"];
    $nomTitre = $_GET["titre"];
    if(empty($nomAuteur) && empty($nomEditeur) && empty($nomTitre)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->recupererToutLivres();
    }else if(!empty($nomAuteur) && empty($nomEditeur) && empty($nomTitre)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParAuteur($nomAuteur);
    }else if(!empty($nomTitre) && empty($nomEditeur) && empty($nomAuteur)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParTitre($nomTitre);
    }else if(!empty($nomEditeur) && empty($nomTitre) && empty($nomAuteur)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParEditeur($nomEditeur);
    } else if(!empty($nomTitre) && !empty($nomAuteur) && empty($nomEditeur)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParAuteurEtTitre($nomAuteur, $nomTitre);
    }else if (!empty($nomAuteur) && !empty($nomEditeur) && empty($nomTitre)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParAuteurEtEditeur($nomAuteur, $nomEditeur);
    }else if (!empty($nomTitre) && !empty($nomEditeur) && empty($nomAuteur)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParTitreEtEditeur($nomTitre, $nomEditeur);
    }else if (!empty($nomTitre) && !empty($nomEditeur) && !empty($nomAuteur)){
        $catalogue = new Bibliotheque;
        $livres = $catalogue->filtrerParTitreEtEditeurEtAuteur($nomTitre, $nomEditeur, $nomAuteur);
    }


 ?>

 <section class="bibliotheque">
 <?php 
 if($_SESSION['Type'] == 'Employe'){
    echo "<a href='/ajout_livres.php' class='yellow_link'>Ajouter un livre</a>";
 }
 ?>

     <div class="filtres">
         <form action="/livres.php" method="GET">
             <h3>Filtres: </h3>
             <label for="auteur">Par auteur : </label>
             <input type="text" id="auteur" name="auteur" />
             <label for="titre">Par titre :</label>
             <input type="text" id="titre" name="titre"/>
             <label for="editeur">Par éditeur :</label>
             <input type="text" id="editeur" name="editeur" />
             <input type="submit" value="Filtrer" />

         </form>
     </div>
     <div>
     <?php
        if(!empty($livres)){
            echo 'Nous avons ' . count($livres) . " livre(s) correspondant à votre recherche.";
        }else {
            echo "Désolé, nous n'avons pas de livres correspondant à votre recherche";
        }
        ?>
        </div>
        <?php

        foreach($livres as $livre){
            echo "<div class='livre_container'>";
            echo  "<img src='".$livre['Image']. " ' class='imgLivre'  height='150' width='100'/>";
            echo "<p class='livre_desc'><span class='titre'>". $livre['Titre'] ."</span><br /><span class='auteur'>De: " . $livre["Auteur"] . "</span></p> ";
            if(isset($_SESSION['Type']) && $_SESSION['Type'] == 'Membre'){
                echo "<form method='GET' action='/reservation.php'>";
                echo "<input type='hidden' name='isbn' value='" . $livre["ISBN"] . " ' />";    
                $estEmprunte = $catalogue->verifierSiLivreEstDispo($livre['ISBN']);
                if(!$estEmprunte){
                echo '<input type="submit"  class="reserver_btn" value="Réserver" >';                                
                }else{
                    echo 'Livre non disponible.';
                }          
                echo "</form>";
            }
            if(isset($_SESSION['Type']) && $_SESSION['Type'] == "Employe"){
                 echo "<form method='GET' action='/supprimer_livre.php'>";
                echo "<input type='hidden' name='isbn' value='" . $livre["ISBN"] . " ' />";
                echo '<input type="submit"  class="supprimer_btn" value="Supprimer" >';
                echo "</form>";
            }
            echo '</div>';
        }
     ?>
 </section>

 <?php
 include('./inc/footer.php');
  ?>
