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
        if(!$verificationLoginUtilisateur){
          echo "Désolé, ces identifiants ne sont pas corrects.";
        }
    
    }
    if(isset($_SESSION['id'])){
        $infosUtilisateur = new Utilisateur;
        $infoInscription = $infosUtilisateur->recupererInfosInscription($_SESSION['id']);
        $infoCompte = $infosUtilisateur->recupererInfoCompte($_SESSION['id']);
        $infoEmprunts = $infosUtilisateur->recupererInfosEmprunts($_SESSION['id']);
        $empruntsPasses = $infosUtilisateur->recupererEmpruntsPasses($_SESSION['id']);
        $penalites= $infosUtilisateur->recupererMontantPenalites($_SESSION['id']);
        $sommeaPayer = $penalites + $infoInscription['PrixMensuel'];
        if(!empty($_POST['ajout_email'])){
            $ajout_email = $infosUtilisateur->ajouterNouvelEmail($_SESSION['id'], $_POST["ajout_email"]);
            if(!$ajout_email){
                echo '<p>Désolé, une erreur est survenue. Veuillez réessayez plus tard.</p>';
            }else {
                header('Refresh:0');
            }
        }
    }
    if(!empty($_GET['mail'])){
      $mail = $_GET['mail'];
      $mdpPerdu = new Utilisateur;
      $nouveauMdp = $mdpPerdu->genererNouveauMdp();
      $compteUtilisateur = $mdpPerdu->verifierMailUtilisateur($mail);
      $nouveauSafeMdp = $mdpPerdu->hachageMotDePasse($nouveauMdp);
      if(!$compteUtilisateur){
        $compteEmploye = $mdpPerdu->verifierMailEmploye($mail);
      }
      if($compteUtilisateur){
        $changerMdp = $mdpPerdu->updateMdpUtilisateur($mail, $nouveauSafeMdp);
        if(!$changerMdp){
          echo 'Une erreur est survenue.';
          return;
        }
      }else if($compteEmploye){
        $changerMdp = $mdpPerdu->updateMdpEmploye($mail, $nouveauSafeMdp);
        if(!$changerMdp){
          echo 'Une erreur est survenue.';
          return;
        }
      }
      $envoiMail = $mdpPerdu->envoiMailNouveauMdp($mail, $nouveauMdp);
      echo $envoiMail;
    }

    if(!empty($_POST['prenomReglement']) && !empty($_POST['nomReglement'])){
      $nouveauReglement = new Utilisateur;
      $utilisateur_a_regle = $nouveauReglement->ajouterReglementMensuel($_POST['prenomReglement'], $_POST['nomReglement']);
      echo $utilisateur_a_regle;
    }

 ?>


 <section class="compte">

     <?php
        if(isset($_SESSION["Prenom"]) && isset($_SESSION["Nom"])){
          if($_SESSION['Type'] == 'Employe'){
            $reglements = new Utilisateur;
            $encoreAregler = $reglements->recupererUtilisateurQuiNontPasPaye();
             echo '<h3>Bienvenue sur votre zone Employe, ' .$_SESSION["Prenom"] .' ' . $_SESSION['Nom'] .'</h3>';
            echo "<a href='/gestion_emprunts.php' class='yellow_link'>Gestion Emprunts</a><br>";
            echo "<a href='/gestion_utilisateurs.php' class='yellow_link'>Gestion Utilisateurs</a><br>";
            echo "<a href='/changer_email.php' class='yellow_link'>Changer Email</a><br>";
            echo "<a href='/changer_password.php' class='yellow_link'>Changer Mot de Passe</a>";
            echo "<h4>Utilisateurs n'ayant pas encore reglé ce mois-ci</h4>";
            foreach($encoreAregler as $user){
              echo "<p>" . $user["Prenom"] . " " . $user['Nom'] ;
              echo "<form method='POST' action='#'>";
              echo "<input type='hidden' name='prenomReglement' value='". $user['Prenom'] . "'>";
              echo "<input type='hidden' name='nomReglement' value='" . $user['Nom'] .  "'>";
              echo "<input class='submit_reglement' type='submit' value='A reglé' >";
            }
          }else if($_SESSION['Type'] == 'Membre' || $_SESSION['Type'] == 'Visiteur' ){
              echo '<h3>Bienvenue sur votre compte, ' .$_SESSION["Prenom"] .' ' . $_SESSION['Nom'] .'</h3>';
              ?>
              <h2>Détails de votre inscription : </h2>
              <?php
                 echo "Libellé de l'inscription : " .$infoInscription['Libelle'] . "<br />";
                 echo "Nombre de livres pouvant être empruntés par mois : " .$infoInscription['NombreEmprunts'] . "<br />";
                 echo 'Prix mensuel: ' .$infoInscription['PrixMensuel']. "€<br />";
                 echo "Montant des pénalités ce mois: " .$penalites . "€<br/>";
                 echo "Somme Total à régler: " . $sommeaPayer . "€";
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
                          <th>Date Limite</th>
                      </tr>
                          <?php
                              foreach($infoEmprunts as $emprunt){
                                  echo "<tr><td>";
                                  echo  $emprunt['Titre'] ."</td>";
                                  echo "<td>" .  $emprunt['Auteur'] . "</td>";
                                  echo "<td>". $emprunt['Editeur'] ."</td>";
                                  echo "<td>" . $emprunt['Debut_Emprunt'] . "</td>";
                                  echo "<td> ". $emprunt["Fin_Emprunt"] ."</td>";
                                  echo "</tr>";
                              }
                              echo '</table>';
                              }else{
                                  echo "<p>Vous n'avez pas d'emprunts en cours</p>";
             }
             if(count($empruntsPasses) != 0 && $infoInscription['Libelle'] != 'Visiteur'){
              ?>
              <h3>Emprunts Passés: </h3>
              <table>
                <tr>
                  <th>Titre</th>
                  <th>Auteur</th>
                  <th>Retourné le:</th>
                </tr>
                <?php 
                  foreach($empruntsPasses as $emprunt){
                    echo "<tr><td>";
                    echo $emprunt['Titre']."</td>";
                    echo "<td>" . $emprunt['Auteur']."</td>";
                    echo "<td>".$emprunt['DateRendu']."</td>";
                    echo '</tr>';
                  }
                  echo "</table>";
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
                <a href="#" class="yellow_link" id='oubli_mdp'>Mot de Passe oublié</a>
                </div>
                <input type="password" id="password" name="password" required/>
                <input type="submit" value="Se Connecter" />
            </form>
            <form id="mail_mdp" action="#" method="GET" >
                <label for="mail">Adresse Email: </label>
                <input type="email" id="mail" name="mail">
                <input type="submit" value="Envoi Mail">
            </form>

            <div class="appel_creation">
                <p>Pas encore inscrit?</p>
                <a href="/creation_compte.php" class="yellow_link">Créer un compte</a>

            </div>

            <?php
        }
        ?>
</section>
<script type="text/javascript">
    var submit_reglement = document.getElementsByClassName('submit_reglement');
    console.log(submit_reglement);
    for(var i = 0; i < submit_reglement.length; i++){
      submit_reglement[i].onclick = function(e){
        var reglement = confirm("Cet utilisateur a reglé sa cotisation et ses pénalités. Confirmer?");
        if(!reglement){
          e.preventDefault();
        }
      }
    }
    
    var link_oubli = document.getElementById("oubli_mdp");
    var oubli_form = document.getElementById('mail_mdp');
    oubli_form.style.display = 'none';
    link_oubli.onclick = function(){
      if(oubli_form.style.display == 'none'){
        oubli_form.style.display = 'block';
      }else{
        oubli_form.style.display = 'none';
      }
    }


</script>

<?php
include 'inc/footer.php' ;
 ?>
