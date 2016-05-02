<?php
    include('inc/header.php');
 ?>

<section class="creation_compte">
    <h3>Création de votre compte</h3>
    <form method="POST" action="#">
        <label for="prenom">Prénom: </label>
        <input type="text" id="prenom" name="prenom" required/>
        <label for="nom">Nom: </label>
        <input type="text" id="nom" name="nom" required/>
        <label for="email">Email : </label>
        <input type="email" id='email' name="email" />
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
