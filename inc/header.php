<?php
    $path = $_SERVER["REQUEST_URI"];
 ?>

<!DOCTYPE html>
<html>
    <head>
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
