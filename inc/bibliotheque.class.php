<?php

Class Bibliotheque{
    //connection à la base de données
    private $_db;

    

    function __construct($db=NULL){
        if(is_object($db)){
            $this->_db = $db;
        }else {
            $this->_db = new PDO("mysql:host=localhost;dbname=Bibliotheque;charset=utf8", "root", "root");
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

    function verifierSiLivreEstDispo($isbn){
        $sql = $this->_db->prepare("SELECT COUNT(*) AS Count FROM Emprunts WHERE ISBN = '$isbn' AND DateRendu IS NULL");
        if(!$sql->execute()){
            return FALSE;
        }else{
            $row = $sql->fetch();
            if($row['Count'] == '0'){
                return FALSE;
            }else {
                return TRUE;
            }
        }
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

    function recupererInfosLivre($isbn){
        $sql = "SELECT * FROM Livre WHERE ISBN = '$isbn'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $livre = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue. Veuillez réessayer plus tard.';
        }
        return $livre[0];
    }

    function supprimerLivre($isbn){
        $sql = $this->_db->prepare("DELETE FROM Livre WHERE ISBN = :isbn");
        $sql->bindParam(':isbn', $isbn);
        if(!$sql->execute()){
            return "Une erreur s'est produite, veuillez réessayez plus tard.";
        }else{
            return "Le livre a bien été supprimé.";
        }
    }


    function filtrerParAuteur($nomAuteur){
        $filtre = "%$nomAuteur%";
        $sql = "SELECT * FROM Livre WHERE Auteur LIKE :filtre ";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtre', $filtre);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue .';
        }
        return $livres;
    }

    function filtrerParTitre($nomTitre){
        $filtre = "%$nomTitre%";
        $sql="SELECT * FROM Livre WHERE Titre LIKE :filtre";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtre', $filtre);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue';
        }
        return $livres;
    }

    function filtrerParEditeur($nomEditeur){
        $filtre = "%$nomEditeur%";
        $sql="SELECT * FROM Livre WHERE Editeur LIKE :filtre";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtre', $filtre);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue';
        }
        return $livres;
    }

    function filtrerParGenre($nomGenre){
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE ID_Genre = :IDGenre");
        $sql->bindParam(':IDGenre', $nomGenre);
        if(!$sql->execute()){
            return 'Désolé, une erreur est survenue.';
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParAuteurEtGenre($nomAuteur, $nomGenre){
        $filtreA = "%$nomAuteur%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Auteur LIKE :filtreA AND ID_Genre = :IDGenre");
        $sql->bindParam(':filtreA', $filtreA);
        $sql->bindParam(':IDGenre', $nomGenre);
        if(!$sql->execute()){
            return 'Désolé, une erreur est survenue.';
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParEditeurEtGenre($nomEditeur, $nomGenre){
        $filtreE = "%$nomEditeur%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Editeur LIKE :filtreE AND ID_Genre = :IDGenre");
        $sql->bindParam(":filtreE", $filtreE);
        $sql->bindParam(':IDGenre', $nomGenre);
        if(!$sql->execute()){
            return 'Désolé, une erreur est survenue.';
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParTitreEtGenre($nomTitre, $nomGenre){
        $filtreT = "%$nomTitre%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Titre LIKE :filtreT AND ID_Genre = :IDGenre");
        $sql->bindParam(":filtreT", $filtreT);
        $sql->bindParam(":IDGenre", $nomGenre);
        if(!$sql->execute()){
            return 'Désolé, une erreur est survenue.';
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParAuteurEtEditeurEtGenre($nomAuteur, $nomEditeur,$nomGenre){
        $filtreA = "%$nomAuteur%";
        $filtreE = "%$nomEditeur%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Auteur LIKE :filtreA AND Editeur LIKE :filtreE AND ID_Genre = :IDGenre");
        $sql->bindParam(":filtreA", $filtreA);
        $sql->bindParam(":filtreE", $filtreE);
        $sql->bindParam(":IDGenre", $nomGenre);
        if(!$sql->execute()){
            return "Désolé, une erreur est survenue.";
        }else {
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParAuteurEtTitreEtGenre($nomAuteur, $nomTitre, $nomGenre){
        $filtreA = "%$nomAuteur%";
        $filtreT = "%$nomTitre%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Auteur LIKE :filtreA AND Titre LIKE :filtreT AND ID_Genre = :IDGenre");
        $sql->bindParam(":filtreA", $filtreA);
        $sql->bindParam(':filtreT', $filtreT);
        $sql->bindParam(':IDGenre', $nomGenre);
        if(!$sql->execute()){
            return "Désolé, une erreur est survenue.";
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParEditeurEtTitreEtGenre($nomEditeur, $nomTitre, $nomGenre){
        $filtreE = "%$nomEditeur%";
        $filtreT = "%$nomTitre%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Editeur LIKE :filtreE AND Titre LIKE :filtreT AND ID_Genre = :IDGenre");
        $sql->bindParam(":filtreE", $filtreE);
        $sql->bindParam(':filtreT', $filtreT);
        $sql->bindParam(":IDGenre", $nomGenre);
        if(!$sql->execute()){
            return "Désolé, une erreur est survenue.";
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }

    function filtrerParAuteurEtEditeurEtTitreEtGenre($nomAuteur, $nomEditeur, $nomTitre, $nomGenre){
        $filtreA = "%$nomAuteur%";
        $filtreE = "%$nomEditeur%";
        $filtreT = "%$nomTitre%";
        $sql = $this->_db->prepare("SELECT * FROM Livre WHERE Auteur LIKE :filtreA AND Editeur LIKE :filtreE AND Titre LIKE :filtreT AND ID_Genre = :IDGenre");
        $sql->bindParam(":filtreA", $filtreA);
        $sql->bindParam(":filtreE", $filtreE);
        $sql->bindParam(":filtreT", $filtreT);
        $sql->bindParam(":IDGenre", $nomGenre);
        if(!$sql->execute()){
            return "Désolé, une erreur est survenue.";
        }else{
            $livres = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $livres;
        }
    }


    function filtrerParAuteurEtTitre($nomAuteur, $nomTitre){
        $filtreA = "%$nomAuteur%";
        $filtreT = "%$nomTitre%";
        $sql = "SELECT * FROM Livre WHERE Auteur LIKE :filtreA AND Titre LIKE :filtreT ";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtreA', $filtreA);
            $stmt->bindParam(':filtreT', $filtreT);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function filtrerParAuteurEtEditeur($nomAuteur, $nomEditeur){
        $filtreA = "%$nomAuteur%";
        $filtreE = "%$nomEditeur%";
        $sql = "SELECT * FROM Livre WHERE Auteur LIKE :filtreA AND Editeur LIKE :filtreE ";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtreA', $filtreA);
            $stmt->bindParam(':filtreE', $filtreE);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function filtrerParTitreEtEditeur($nomTitre, $nomEditeur){
        $filtreT = "%$nomTitre%";
        $filtreE = "%$nomEditeur%";
        $sql = "SELECT * FROM Livre WHERE Titre LIKE :filtreT AND Editeur LIKE :filtreE";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtreT', $filtreT);
            $stmt->bindParam(':filtreE', $filtreE);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function filtrerParTitreEtEditeurEtAuteur($nomTitre, $nomEditeur, $nomAuteur){
        $filtreT = "%$nomTitre%";
        $filtreE = "%$nomEditeur%";
        $filtreA = "%$nomAuteur%";
        $sql = "SELECT * FROM Livre WHERE Titre LIKE :filtreT AND Editeur LIKE :filtreE
        AND Auteur LIKE :filtreA";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':filtreT', $filtreT);
            $stmt->bindParam(':filtreE', $filtreE);
            $stmt->bindParam(':filtreA', $filtreA);
            $stmt->execute();
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, une erreur est survenue.';
        }
        return $livres;
    }

    function recupererGenres(){
        $sql = "SELECT * FROM Genre";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return 'Désolé, nous n\'avons pas pu récupérer les genres pour le moment.';
        }
        return $genres;
    }

    function recupererLangues(){
        $sql = "SELECT * FROM Langue";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $langues = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return "Désolé, nous n'avons pas récupérer les langues pour le moment.";
        }
        return $langues;
    }

    function ajouterLivre($isbn, $titre, $auteur, $editeur, $annee, $idGenre, $idLangue, $image){
        $sql = $this->_db->prepare("INSERT INTO Livre (ISBN, Titre, Auteur, AnneePublication, Editeur, ID_Langue, ID_Genre, Image) VALUES (:isbn, :titre, :auteur, :annee, :editeur, '$idLangue', '$idGenre', :image)");
        
        $sql->bindParam(':isbn', $isbn);
        $sql->bindParam(':titre', $titre);
        $sql->bindParam(':auteur', $auteur);
        $sql->bindParam(':annee', $annee);
        $sql->bindParam(':editeur', $editeur);
        $sql->bindParam(':image', $image);
        if(!$sql->execute()){
            return 'Désolé, un erreur s\'est produite, veuillez vérifier les informations.';
        }else{
             return $titre . " a bien été ajouté à la bibliothèque.";
        }
        
    }

    function recupererToutLesEmprunts(){
        $sql = $this->_db->prepare('SELECT Emprunts.ISBN, Emprunts.ID, Titre, Nom, Prenom, Debut_Emprunt, Fin_Emprunt FROM Emprunts JOIN Utilisateur JOIN Livre WHERE Emprunts.ISBN = Livre.ISBN AND Emprunts.ID_Utilisateur = Utilisateur.ID AND Emprunts.DateRendu IS NULL');
        if(!$sql->execute()){
            return "Les emprunts n'ont pas pu être récuperés.";
        }else{
            $emprunts = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $emprunts;
        }
    }

    function validerRetourEmprunt($isbn){
        $date = date('Y m d');
        $sql = $this->_db->prepare("UPDATE Emprunts SET DateRendu = STR_TO_DATE('$date', ' %Y %m %d ') WHERE ISBN = '$isbn'");
        if(!$sql->execute()){
            return "Une erreur est survenue. Veuilez réessayez plus tard.";
        }else {
            return "La date de retour a bien été enregistrée.";
        }
    }

    function ajouterEmprunt($isbn, $id, $date){
        $sql = $this->_db->prepare("INSERT INTO Emprunts (ID_Utilisateur, ISBN, Debut_Emprunt, Fin_Emprunt)
         VALUES('$id', :isbn, '$date', DATE_ADD('$date', INTERVAL 2 WEEK))");
        $sql->bindParam(":isbn", $isbn);
        if(!$sql->execute()){
            return "Une erreur est survenue. Veuillez vérifier l'isbn ou le nom de l'emprunteur.";
        }else{
            return "L'emprunt a été enregistré.";
        }
    }
}