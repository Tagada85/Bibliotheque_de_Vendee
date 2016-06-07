<?php
    include('inc/header.php');
    $creation_compte = new Utilisateur;
    $prenom_compte = $_POST["prenom"];
    $nom_compte = $_POST["nom"];
    $dateNaissance_compte = $_POST["dateNaissance"];
    $mdp_compte = $_POST["password"];

    if(!empty($prenom_compte) && !empty($nom_compte) && !empty($dateNaissance_compte) && !empty($mdp_compte)){
        $estDejaMembre = $creation_compte->verifierSiCompteEstDejaMembre($prenom_compte, $nom_compte, $dateNaissance_compte);
        $estEmploye = $creation_compte->verifierSiCompteEstEmploye($prenom_compte, $nom_compte, $dateNaissance_compte);
        $safe_mdp = $creation_compte->hachageMotDePasse($mdp_compte);
        if($estDejaMembre){
            $compteMembre = $creation_compte->changerCompteMembre($nom_compte, $prenom_compte, $safe_mdp);
        }else if($estEmploye){
            $compteEmploye = $creation_compte->changerCompteEmploye($nom_compte, $prenom_compte,$safe_mdp);
        }else{
            $compteVisiteur = $creation_compte->creationNouveauCompteVisiteur($prenom_compte, $nom_compte, $dateNaissance_compte, $safe_mdp);
        }
    }
 ?>

<section class="creation_compte">
    <h3>Création de votre compte</h3>
    <p>
        Si vous possédez une carte de membre, veuillez utiliser les mêmes identifiants pour créer votre compte.
    </p>
    <form method="POST" action="#">
        <label for="prenom">Prénom: </label>
        <input type="text" id="prenom" name="prenom" required/>
        <label for="nom">Nom: </label>
        <input type="text" id="nom" name="nom" required/>
        <label for="dateNaissance">Date de Naissance : </label>
        <input type="date" id='dateNaissance' name="dateNaissance" />
        <label for="password">Mot de Passe:</label>
        <input type="password" id="password" name="password" required/>
        <label for="confirm_password">Confirmer Mot de Passe: </label>
        <input type="password" id="confirm_password" name="confirm_password" required/>
        <input type="submit" id="submitBtn" />
    </form>
</section>

<script>
    var passwordInput = document.getElementById('password');
    var confirm_pass = document.getElementById('confirm_password');


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
