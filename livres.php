<?php
    include('inc/header.php');
    require_once('inc/bibliotheque.class.php');
    $nomAuteur = $_GET["auteur"];
    $nomEditeur = $_GET["editeur"];
    $nomTitre = $_GET["titre"];
    $nomGenre = $_GET['genre'];
    $catalogue = new Bibliotheque;
    $genres = $catalogue->recupererGenres();
    if(empty($nomAuteur) && empty($nomEditeur) && empty($nomTitre)  && empty($nomGenre)){
        $livres = $catalogue->recupererToutLivres();
    }else if(!empty($nomAuteur) && empty($nomEditeur) && empty($nomTitre) && empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteur($nomAuteur);
    }else if(!empty($nomTitre) && empty($nomEditeur) && empty($nomAuteur) && empty($nomGenre)){
        $livres = $catalogue->filtrerParTitre($nomTitre);
    }else if(!empty($nomEditeur) && empty($nomTitre) && empty($nomAuteur) && empty($nomGenre)){
        $livres = $catalogue->filtrerParEditeur($nomEditeur);
    } else if(empty($nomEditeur) && empty($nomTitre) && empty($nomAuteur) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParGenre($nomGenre);
    }else if(!empty($nomTitre) && !empty($nomAuteur) && empty($nomEditeur) && empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteurEtTitre($nomAuteur, $nomTitre);
    }else if (!empty($nomAuteur) && !empty($nomEditeur) && empty($nomTitre) && empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteurEtEditeur($nomAuteur, $nomEditeur);
    }else if (!empty($nomTitre) && !empty($nomEditeur) && empty($nomAuteur) && empty($nomGenre)){
        $livres = $catalogue->filtrerParTitreEtEditeur($nomTitre, $nomEditeur);
    }else if (!empty($nomTitre) && !empty($nomEditeur) && !empty($nomAuteur) && empty($nomGenre)){
        $livres = $catalogue->filtrerParTitreEtEditeurEtAuteur($nomTitre, $nomEditeur, $nomAuteur);
    }else if(!empty($nomAuteur) && empty($nomEditeur) && empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteurEtGenre($nomAuteur, $nomGenre);
    }else if(empty($nomAuteur) && !empty($nomEditeur) && empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParEditeurEtGenre($nomEditeur, $nomGenre);
    }else if(empty($nomAuteur) && empty($nomEditeur) && !empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParTitreEtGenre($nomTitre, $nomGenre);
    }else if(!empty($nomAuteur) && !empty($nomEditeur) && empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteurEtEditeurEtGenre($nomAuteur, $nomEditeur,$nomGenre);
    }else if(!empty($nomAuteur) && empty($nomEditeur) && !empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteurEtTitreEtGenre($nomAuteur, $nomTitre, $nomGenre);
    }else if(empty($nomAuteur) && !empty($nomEditeur) && !empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParEditeurEtTitreEtGenre($nomEditeur, $nomTitre, $nomGenre);
    }else if(!empty($nomAuteur) && !empty($nomEditeur) && !empty($nomTitre) && !empty($nomGenre)){
        $livres = $catalogue->filtrerParAuteurEtEditeurEtTitreEtGenre($nomAuteur, $nomEditeur, $nomTitre, $nomGenre);
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
             <label for="genre">Genre: </label>
             <select name="genre" id="genre">
                 <option value="#" disabled selected> --Choisissez un genre --</option>
                 <?php 
                    foreach($genres as $genre){
                        echo "<option value='" . $genre['ID'] ."'>" . $genre['Libelle'] . "</option>";
                    }
                 ?>
             </select>
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
