<?php
    include('inc/header.php');
    if(!empty($_POST['mdp_actuel']) && !empty($_POST["nouveau_mdp"]) && !empty($_POST['confirm_mdp'])){
        $current_mdp = $_POST["mdp_actuel"];
        $new_mdp = $_POST['nouveau_mdp'];
        $changementMdp = new Utilisateur;
        $safe_mdp = $changementMdp->hachageMotDePasse($new_mdp);
        if($_SESSION['Type'] != 'Employe'){
        $controleAncienMdp = $changementMdp->controleAncienMdpUtilisateur($_SESSION['id'], $current_mdp);    
        }else{
            $controleAncienMdp = $changementMdp->controleAncienMdpEmploye($_SESSION['Prenom'], $_SESSION['Nom'], $current_mdp);
        }
        if(!$controleAncienMdp){
            echo '<p>Votre mot de passe actuel ne correspond pas à celui indiqué!</p>';
        }else {
            if($_SESSION['Type'] != 'Employe'){
            $insererNouveauMdp = $changementMdp->changerMdpUtilisateur($_SESSION['id'] , $safe_mdp);
            }else{
                $insererNouveauMdp = $changementMdp->changerMdpEmploye($_SESSION['Prenom'], $_SESSION['Nom'], $safe_mdp);
            }
            echo $insererNouveauMdp;
        }
    }
 ?>

<section>
    <h2>Changement de mot de passe: </h2>
    <form action="#" method="POST">
        <label for="mdp_actuel">Mot de passe actuel:</label>
        <input type="password" id="mdp_actuel" name="mdp_actuel" />
        <label for="nouveau_mdp">Nouveau mot de passe:</label>
        <input type="password" id="nouveau_mdp" name="nouveau_mdp"/>
        <label for="confirm_mdp">Ré-entrez nouveau mot de passe:</label>
        <input type="password" id="confirm_mdp" name="confirm_mdp"/>
        <input type="submit" value="Valider" />
    </form>
</section>

<script>
    var passwordInput = document.getElementById('nouveau_mdp');
    var confirm_pass = document.getElementById('confirm_mdp');


function validatePassword(){
  if(passwordInput.value != confirm_pass.value) {
    confirm_pass.setCustomValidity("Les mots de passes ne correspondent pas!.");
  } else {
    confirm_pass.setCustomValidity('');
  }
}

passwordInput.onchange = validatePassword;
confirm_pass.onkeyup = validatePassword;
</script>

 <?php
     include('inc/footer.php');
  ?>
