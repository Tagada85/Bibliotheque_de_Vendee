<?php
    include('inc/header.php');
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST["password"])){
        $nouvelleConnexion = new Utilisateur;
        $nom_login = $_POST['nom'];
        $prenom_login = $_POST['prenom'];
        $mdp_login = $_POST['password'];
        $verificationLoginUtilisateur = $nouvelleConnexion->verificationIdentifiantsUtilisateur($nom_login, $prenom_login, $mdp_login);
        if(!$verificationLoginUtilisateur){
          $verificationLoginEmploye = $nouvelleConnexion->verificationIdentifiantsEmploye($nom_login, $prenom_login, $mdp_login);
        }
        if(!$verificationLoginEmploye){
          echo "Désolé, ces identifiants ne sont pas corrects.";
        }
    
    }
    if(isset($_SESSION['id'])){
        $infosUtilisateur = new Utilisateur;
        $infoInscription = $infosUtilisateur->recupererInfosInscription($_SESSION['id']);
        $infoCompte = $infosUtilisateur->recupererInfoCompte($_SESSION['id']);
        $infoEmprunts = $infosUtilisateur->recupererInfosEmprunts($_SESSION['id']);
        $penalites= $infosUtilisateur->recupererMontantPenalites($_SESSION['id']);
        var_dump($penalites);
        if(!empty($_POST['ajout_email'])){
            $ajout_email = $infosUtilisateur->ajouterNouvelEmail($_SESSION['id'], $_POST["ajout_email"]);
            if(!$ajout_email){
                echo '<p>Désolé, une erreur est survenue. Veuillez réessayez plus tard.</p>';
            }else {
                header('Refresh:0');
            }
        }
    }

 ?>


 <section class="compte">

     <?php
        if(isset($_SESSION["Prenom"]) && isset($_SESSION["Nom"])){
          if($_SESSION['Type'] == 'Employe'){
             echo '<h3>Bienvenue sur votre zone Employe, ' .$_SESSION["Prenom"] .' ' . $_SESSION['Nom'] .'</h3>';
            echo "<a href='/gestion_emprunts.php' class='yellow_link'>Gestion Emprunts</a><br>";
            echo "<a href='/gestion_utilisateurs.php' class='yellow_link'>Gestion Utilisateurs</a><br>";
            echo "<a href='/changer_email.php' class='yellow_link'>Changer Email</a><br>";
            echo "<a href='/changer_password.php' class='yellow_link'>Changer Mot de Passe</a>";
          }else if($_SESSION['Type'] == 'Membre' || $_SESSION['Type'] == 'Visiteur' ){
              echo '<h3>Bienvenue sur votre compte, ' .$_SESSION["Prenom"] .' ' . $_SESSION['Nom'] .'</h3>';
              ?>
              <h2>Détails de votre inscription : </h2>
              <?php
                 echo "Libellé de l'inscription : " .$infoInscription['Libelle'] . "<br />";
                 echo "Nombre de livres pouvant être empruntés par mois : " .$infoInscription['NombreEmprunts'] . "<br />";
                 echo 'Prix mensuel: ' .$infoInscription['PrixMensuel']. "€<br />";
              ?>
              <h2>Détails de votre compte: </h2>
              <?php
                 echo "Nom : " .$infoCompte['Nom']."<br />";
                 echo "Prenom : " .$infoCompte['Prenom']."<br />";
                 if($infoCompte['Adresse'] != NULL){
                     echo "Adresse : " .$infoCompte['Adresse']." " .$infoCompte['CodePostal'] ." ". $infoCompte['Ville']. "<br />";
                 }

                 if($infoCompte['Email'] != NULL){
                     echo "Email : " .$infoCompte['Email'];
                     echo "<a href='changer_email.php' class='yellow_link'>  Changer Email</a><br />";
                 }else{
                     echo "<p>Vous n'avez pas renseigné d'email.</p>";
                     include('inc/email_form.php');
                 }

                 if($infoInscription['Libelle'] != 'Visiteur'){
                     echo '<a href="/changer_password.php" class="yellow_link">Changer Mot de Passe</a>';
                 }
                 if(count($infoEmprunts) != 0 && $infoInscription['Libelle'] != 'Visiteur'){
                  ?>
                  <h3>Emprunts en cours: </h3>
                  <table>
                      <tr>
                          <th>Titre</th>
                          <th>Auteur</th>
                          <th>Editeur</th>
                          <th>Emprunté le</th>
                          <th>Rendu le</th>
                          <th>Date Limite</th>
                      </tr>
                          <?php
                              foreach($infoEmprunts as $emprunt){
                                  echo "<tr><td>";
                                  echo  $emprunt['Titre'] ."</td>";
                                  echo "<td>" .  $emprunt['Auteur'] . "</td>";
                                  echo "<td>". $emprunt['Editeur'] ."</td>";
                                  echo "<td>" . $emprunt['Debut_Emprunt'] . "</td>";
                                  echo "<td>" . $emprunt['DateRendu'] . "</td>";
                                  echo "<td> ". $emprunt["Fin_Emprunt"] ."</td>";
                                  echo "</tr>";
                              }
                              echo '</table>';
                              }else{
                                  echo "<p>Vous n'avez pas d'emprunts en cours</p>";
             }

         }
        } else {
            ?>
            <h3>Entrez vos identifiants pour consulter votre profil.</h3>
            <form method="POST" action="#">
                <label for="prenom">Prénom: </label>
                <input type="text" id="prenom" name="prenom" required/>
                <label for="nom">Nom: </label>
                <input type="text" id="nom" name="nom" required/>
                <label for="password">Mot de Passe: </label>
                <a href="/oubli_mdp.php" class="yellow_link">Mot de Passe oublié</a>
                <input type="password" id="password" name="password" required/>
                <input type="submit" value="Se Connecter" />
            </form>

            <div class="appel_creation">
                <p>Pas encore inscrit?</p>
                <a href="/creation_compte.php" class="yellow_link">Créer un compte</a>

            </div>

            <?php
        }
        ?>
</section>

<?php
include 'inc/footer.php' ;
 ?>
