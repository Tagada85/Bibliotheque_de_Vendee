<?php
    include('inc/header.php');
    if(isset($_POST['email_actuel']) && isset($_POST["nouvel_email"])){
        $demande_changement = new Utilisateur;
        if($_SESSION['Type'] != 'Employe'){
            $ancienEmailCorrespond = $demande_changement->controleAncienEmailUtilisateur($_SESSION['id'], $_POST["email_actuel"]);
        }else{
            $ancienEmailCorrespond = $demande_changement->controleAncienEmailEmploye($_SESSION['Prenom'], $_SESSION['Nom'], $_POST["email_actuel"]);
        }
        
        if(!$ancienEmailCorrespond){
            echo "<p>Désolé, votre adresse email actuelle ne correspond pas à celle indiquée lors de la création de votre compte.</p>";
        }else {
            if($_SESSION['Type'] != 'Employe'){
                 $changementEmail = $demande_changement->changementEmailUtilisateur($_SESSION['id'], $_POST['nouvel_email']);     
            }else{
                $changementEmail = $demande_changement->changementEmailEmploye($_SESSION['Prenom'], $_SESSION['Nom'], $_POST['nouvel_email']);     
            }
            echo $changementEmail;
        }
    }
    

 ?>
 <section>
    <h2>Changement d'adresse email</h2>
    <p>Veuillez saisir votre adresse email actuelle et votre nouvelle adresse email</p>
    <form action="#" method="POST">
        <label for="adresse_actuelle">Adresse actuelle: </label>
        <input type="email" name="email_actuel" id="adresse_actuelle" />
        <label for="nouvelle_adresse">Nouvelle Adresse</label>
        <input type="email" name="nouvel_email" id="nouvelle_adresse" />
        <input type="submit" value="Changer Email"/>

    </form>
</section>

 <?php
     include('inc/footer.php');
  ?>
