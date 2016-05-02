<?php
    include('inc/header.php');
    $_SESSION['username'] = null;
 ?>

 <section class="compte">

<?php //Si l'utilisateur est connecté
        //Montrer ses infos de profil



        //pas connecté : lui proposer de créer un compte
        // s'il est déja membre, lui proposer de se connecter
        if(isset($_SESSION["username"])){
            ?>
            <h3>Bienvenue sur votre compte, <?php echo $_SESSION["username"] ?>.</h3>
            <?php
        } else {
            ?>
            <h3>Entrez vos identifiants pour consulter votre profil.</h3>
            <form method="GET" action="#">
                <label for="prenom">Prénom: </label>
                <input type="text" id="prenom" name="prenom" required/>
                <label for="nom">Nom: </label>
                <input type="text" id="nom" name="nom" required/>
                <label for="password">Mot de Passe: </label>
                <input type="password" id="password" name="password" required/>
                <input type="submit" value="Se Connecter" />
            </form>

            <div class="appel_creation">
                <p>Pas encore inscrit?</p>
                <a href="/creation_compte.php">Créer un compte</a>
            </div>

            <?php
        }

?>


 </section>
