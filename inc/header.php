<?php
    session_start();
    $_SERVER['DOCUMENT_ROOT'] = "/home/damienco/public_html/Librairie_de_Vendee";
    $path = $_SERVER["REQUEST_URI"];
    require('inc/utilisateurs.class.php');
    if(isset($_POST['logout']) && $_POST['logout'] == 1){
        unset($_SESSION['Prenom']);
        unset($_SESSION['Nom']);
        unset($_SESSION['Type']);
        unset($_SESSION['id']);
        session_destroy();
        header('Location:index.php');
    }
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html" charset="ISO-8859-1">
        <title><?php
            if($path == '/'){
                echo 'Accueil';
            }else if($path == '/livres.php'){
                echo 'Nos livres';
            }else if($path == '/compte.php'){
                echo 'Votre compte';
            }else if($path == '/calendrier.php'){
                echo 'Calendrier';
            }
         ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>


            <header>
              <div class='logo'>
                  <a href="/">
                      <img src="img/logo.png"/>
                  </a>
              </div>
              <div class="navigation">
                <ul>
                  <li>
                    <a href="/" <?php
                        if($path == '/') {
                             echo 'class="current"';}
                        ?>>
                        Accueil</a>
                  </li>
                  <li>
                    <a href="/livres.php" <?php
                        if($path == '/livres.php') {
                             echo 'class="current"';}
                        ?>>
                        Nos livres</a>
                  </li>
                  <li>
                    <a href="/compte.php"<?php
                        if($path == '/compte.php') {
                             echo 'class="current"';}
                        ?>>
                        Mon compte</a>
                  </li>
                  <li>
                    <a href="/calendrier.php" <?php
                        if($path  == '/calendrier.php'){
                            echo 'class="current"';}
                        ?>>
                        Calendrier</a>
                  </li>
                </ul>
              </div>

            </header>
            <div class="wrapper">
                <?php
                if(isset($_SESSION['Prenom']) && isset($_SESSION['Nom']) && isset($_SESSION['Type'])){
                    $session = new Utilisateur;
                    $id = $session->recupererIdUtilisateur($_SESSION['Nom'], $_SESSION['Prenom']);
                    $_SESSION['id'] = $id;
                    echo '<form action="#" method="POST" class="right">
                            <input type="hidden" name="logout" value="1" />
                            <input type="submit" value="Déconnexion" id="logout"/>
                        </form>';
                };

                 ?>
                <script>
                    var logoutBtn = document.getElementById('logout');
                    logoutBtn.onclick = function(e){
                        var reponse = confirm('Vous allez être déconnecté de votre compte. Continuer ?');
                        if(!reponse){
                            e.preventDefault();
                        }
                    }
                </script>
