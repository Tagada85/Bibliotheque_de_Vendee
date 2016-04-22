<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="wrapper">

            <header>
              <div class='logo'></div>
              <div class="navigation">
                <ul>
                  <li>
                    <a href="/" <?php
                        if($_SERVER["REQUEST_URI"] == '/') {
                             echo 'class="current"';}
                        ?>>
                        Accueil</a>
                  </li>
                  <li>
                    <a href="/livres.php" <?php
                        if($_SERVER["REQUEST_URI"] == '/livres.php') {
                             echo 'class="current"';}
                        ?>>
                        Nos livres</a>
                  </li>
                  <li>
                    <a href="/compte.php"<?php
                        if($_SERVER["REQUEST_URI"] == '/compte.php') {
                             echo 'class="current"';}
                        ?>>
                        Mon compte</a>
                  </li>
                  <li>
                    <a href="/calendrier.php" <?php
                        if($_SERVER["REQUEST_URI"] == '/calendrier.php'){
                            echo 'class="current"';}
                        ?>>
                        Calendrier</a>
                  </li>
                </ul>
              </div>

            </header>
