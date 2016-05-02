<?php

Class Bibliotheque{
    //connection à la base de données
    private $_db;

    function __construct($db=NULL){
        if(is_object($db)){
            $this->_db = $db;
        }else {
            $this->_db = new PDO("mysql:host=localhost;dbname=Bibliotheque", "root", "root");
        }
    }

    //récupérer tous les livres
    function recupererToutLivres(){
        $sql = "SELECT * FROM Livre";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Nous ne pouvons pas aller sur la base de données';
        }
        return $livres;
    }

    //récupérer les offres
    function recupererOffresBibliotheque(){
        $sql = "SELECT * FROM Typeinscription";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return "Désolé, une erreur est survenue et nous empêche de vous montrer nos offres.";
        }
        return $offres;
    }

    //récupérer infos livres réservation
    function recupererLivreReservation($isbn){
        $sql = "SELECT * FROM Livre WHERE ISBN = '$isbn'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livre = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue. Veuillez réessayer plus tard.';
        }
        return $livre;
    }

    //filters function

    function filtrerParAuteur($nomAuteur){
        $sql = "SELECT * FROM Livre WHERE Auteur LIKE '%$nomAuteur%' ";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue .';
        }
        return $livres;
    }

    function filtrerParTitre($nomTitre){
        $sql="SELECT * FROM Livre WHERE Titre LIKE '%$nomTitre%'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue';
        }
        return $livres;
    }

    function filtrerParEditeur($nomEditeur){
        $sql="SELECT * FROM Livre WHERE Editeur LIKE '%$nomEditeur%'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue';
        }
        return $livres;
    }


    function filtrerParAuteurEtTitre($nomAuteur, $nomTitre){
        $sql = "SELECT * FROM Livre WHERE Auteur LIKE '%$nomAuteur%' AND Titre LIKE '%$nomTitre%'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function filtrerParAuteurEtEditeur($nomAuteur, $nomEditeur){
        $sql = "SELECT * FROM Livre WHERE Auteur LIKE '%$nomAuteur%' AND Editeur LIKE '%$nomEditeur%'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function filtrerParTitreEtEditeur($nomTitre, $nomEditeur){
        $sql = "SELECT * FROM Livre WHERE Titre LIKE '%$nomTitre%' AND Editeur LIKE '%$nomEditeur%'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function filtrerParTitreEtEditeurEtAuteur($nomTitre, $nomEditeur, $nomAuteur){
        $sql = "SELECT * FROM Livre WHERE Titre LIKE '%$nomTitre%' AND Editeur LIKE '%$nomEditeur%'
                    AND Auteur LIKE '%$nomAuteur%'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }
}
