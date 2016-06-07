<?php

Class Utilisateur {

    private $_db;

    function __construct($db=NULL){
        if(is_object($db)){
            $this->_db = $db;
        }else {
            $this->_db = new PDO("mysql:host=localhost;dbname=Bibliotheque", "root", "root");
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    function recupererIdUtilisateur($nom, $prenom){
        $sql = "SELECT ID FROM Utilisateur WHERE Nom = '$nom' AND Prenom = '$prenom'";
        if($stmt = $this->_db->query($sql)){
            $id = $stmt->fetch();
            return $id['ID'];
        }
    }

    
    function verifierSiCompteEstDejaMembre($prenom, $nom, $dateNaissance){
        $sql = "SELECT COUNT(ID) AS leMembre FROM Utilisateur
                WHERE Prenom = :prenom AND Nom = :nom AND DateNaissance = :dateNaissance";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
            $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
            $stmt->bindParam(":dateNaissance", $dateNaissance, PDO::PARAM_STR);
            $stmt->execute(); 
            $ligne = $stmt->fetch();
            if($ligne['leMembre'] != 0){
                return TRUE;
            }else {
                return FALSE;
            }
        }
    }

    function verifierSiCompteEstEmploye($prenom, $nom, $dateNaissance){
        $sql = "SELECT COUNT(ID) AS leMembre FROM Employe
                WHERE Prenom = :prenom AND Nom = :nom AND DateNaissance = :dateNaissance";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
            $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
            $stmt->bindParam(":dateNaissance", $dateNaissance, PDO::PARAM_STR);
            $stmt->execute();
            $ligne = $stmt->fetch();
            if($ligne['leMembre'] != 0){
                return TRUE;
            }else {
                return FALSE;
            }
        }
    }

    function hachageMotDePasse($mdp){
        $safe_mdp = password_hash($mdp, PASSWORD_DEFAULT);
        return $safe_mdp;
    }


    
    function creationNouveauCompteVisiteur($prenom, $nom, $dateNaissance,  $mdp){
        $sql = $this->_db->prepare("INSERT INTO Utilisateur (Nom , Prenom , DateNaissance, MotDePasse, TypeInscription)
        VALUES (:nom, :prenom, '$dateNaissance', :mdp, '4')");
             $sql->bindParam(':nom' , $nom, PDO::PARAM_STR);
            $sql->bindParam(':prenom' , $prenom, PDO::PARAM_STR);
            $sql->bindParam(':mdp' , $mdp, PDO::PARAM_STR);
            if(!$sql->execute()){
                return 'Désolé, une erreur est survenue. Veuillez réessayer plus tard.';
            }else {
                session_start();
                $_SESSION['Prenom'] = $prenom;
                $_SESSION['Nom'] = $nom;
                $_SESSION["Type"] = "Visiteur";
                header('Location:../merci_visiteur.php');
            }
    }
            
    function changerCompteMembre($nom, $prenom, $mdp){
        $sql = $this->_db->prepare("UPDATE Utilisateur SET MotDePasse  = :mdp WHERE Nom = :nom AND Prenom = :prenom");
        $sql->bindParam(':mdp', $mdp);
        $sql->bindParam(':nom', $nom);
        $sql->bindParam(':prenom', $prenom);
        if(!$sql->execute()){
            return '<div class="erreur">Désolé, une erreur est survenue. Veuillez réessayer plus tard.</div>';
        }else {
            session_start();
            $_SESSION['Prenom'] = $prenom;
            $_SESSION['Nom'] = $nom;
            $_SESSION['Type'] = 'Membre';
            header('Location:../merci_visiteur.php');
        }
    }


    function changerCompteEmploye($nom, $prenom, $mdp){
        $sql = $this->_db->prepare("UPDATE Employe SET MotDePasse = :mdp WHERE Nom = :nom AND Prenom = :prenom ");
        $sql->bindParam(':mdp', $mdp);
        $sql->bindParam(':nom', $nom);
        $sql->bindParam(':prenom', $prenom);
        if(!$sql->execute()){
              return '<div class="erreur">Désolé, une erreur est survenue. Veuillez réessayer plus tard.</div>';
        } else {
            session_start();
            $_SESSION['Prenom'] = $prenom;
            $_SESSION['Nom'] = $nom;
            $_SESSION['Type'] = 'Employe';
            header('Location:../merci_visiteur.php');   
        }
    }


    function verificationIdentifiantsUtilisateur($nom, $prenom, $mdp){
        $sql = $this->_db->prepare("SELECT * FROM  Utilisateur WHERE Nom = :nom AND Prenom = :prenom");
        $sql->bindParam(':nom', $nom);
        $sql->bindParam(':prenom', $prenom);
        if(!$sql->execute()){
            return FALSE;
        }else {
            $row = $sql->fetch(PDO::FETCH_ASSOC);
        }
        if(!$row){
            return FALSE;
        }
        if(password_verify($mdp, $row['MotDePasse'])){
            session_destroy(); 
            session_start();
            $_SESSION['Prenom'] = $prenom;
            $_SESSION['Nom'] = $nom;
            $_SESSION['id'] = $row['ID'];
            if($row['TypeInscription'] == 4){
                $_SESSION['Type'] = 'Visiteur';
            }else if($row['TypeInscription'] == 6){
                $_SESSION['Type'] = 'Employe';
            }else {
                $_SESSION['Type'] = "Membre";
            }
            header('Location:compte.php');
        }else {
            return '<p>Désolé, ces identifiants ne sont pas corrects.</p>';
        }
    }

    function verificationIdentifiantsEmploye($nom, $prenom, $mdp){
         $sql = $this->_db->prepare("SELECT * FROM  Employe
            WHERE Nom = :nom AND Prenom = :prenom");
            $sql->bindParam(':nom', $nom);
            $sql->bindParam(':prenom', $prenom);
            if(!$sql->execute()){
                 return FALSE;
            }else {
                $row = $sql->fetch();
           }
            if(!$row){
                return FALSE;
            }else{
                if(password_verify($mdp, $row['MotDePasse'])){
                    session_destroy();
                    session_start();
                    $_SESSION['Prenom'] = $prenom;
                    $_SESSION['Nom'] = $nom;
                    $_SESSION['id'] = $row['ID'];
                    if($row['TypeInscription'] == 4){
                        $_SESSION['Type'] = 'Visiteur';
                    }else if($row['TypeInscription'] == 6){
                        $_SESSION['Type'] = 'Employe';
                    }else {
                        $_SESSION['Type'] = "Membre";
                    }
                    header('Location:compte.php');
                }else {
                    return '<p>Désolé, ces identifiants ne sont pas corrects.</p>';
                }


            }
    }

    function recupererInfoCompte($id){
        $sql = "SELECT * FROM Utilisateur WHERE ID = '$id'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $compte = $stmt->fetch();
            return $compte;
        }else{
            return "<p>Nous n'avons pas pu récupérer les infos de votre compte! Réessayez plus tard.</p>";
        }
    }

    function controleAncienMdpUtilisateur($id, $mdp){
        $sql = "SELECT MotDePasse FROM Utilisateur WHERE ID = '$id'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $resultat = $stmt->fetch();
            $motDePasse = $resultat['MotDePasse'];
        }
        if(password_verify($mdp, $motDePasse)){
            return TRUE;
        }else {
            return FALSE;
        }
    }

    function controleAncienMdpEmploye($prenom,$nom,  $mdp){
        $sql = "SELECT MotDePasse FROM Employe WHERE Prenom = '$prenom' AND 
        Nom = '$nom'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $resultat = $stmt->fetch();
            $motDePasse = $resultat['MotDePasse'];
        }
        if(password_verify($mdp, $motDePasse)){
            return TRUE;
        }else {
            return FALSE;
        }
    }

    function changerMdpUtilisateur($id, $mdp){
        $sql = $this->_db->prepare("UPDATE Utilisateur SET MotDePasse = :mdp WHERE ID ='$id'");
        $sql->bindParam(':mdp', $mdp);
        if(!$sql->execute()){
            return '<p>Déolé, une erreur est survenue. Veuillez réessayez plus tard.</p>';
        }else{
            return '<p>Votre mot de passe a bien été mis à jour!</p>';
        }
    }

   function changerMdpEmploye($prenom,$nom, $mdp){
        $sql = $this->_db->prepare("UPDATE Employe SET MotDePasse = :mdp WHERE Prenom ='$prenom' AND 
            Nom = '$nom'");
        $sql->bindParam(':mdp', $mdp);
        if(!$sql->execute()){
            return '<p>Déolé, une erreur est survenue. Veuillez réessayez plus tard.</p>';
        }else{
            return '<p>Votre mot de passe a bien été mis à jour!</p>';
        }
    }

    function controleAncienEmailUtilisateur($id, $email_actuel){
        $sql = $this->_db->prepare("SELECT Email FROM Utilisateur WHERE ID = '$id'");
            $sql->execute();
            $resultat = $sql->fetch();
            $email = $resultat['Email'];
        var_dump($email);
        var_dump($email_actuel);
        var_dump($resultat);
        if($email !== $email_actuel){
            return FALSE;
        }else {
            return TRUE;
        }
    }

      function controleAncienEmailEmploye($prenom, $nom, $email_actuel){
        $sql = $this->_db->prepare("SELECT Email FROM Employe WHERE Prenom = '$prenom' 
            AND Nom = '$nom'");
        $sql->execute();     
        $resultat = $sql->fetch();
        $email = $resultat['Email'];
        var_dump($resultat);
        if($email !== $email_actuel){
            return FALSE;
        }else {
            return TRUE;
        }
    }

    function changementEmailUtilisateur($id, $nouvel_email){
        $sql = $this->_db->prepare("UPDATE Utilisateur SET Email = :email WHERE ID = '$id'");
        $sql->bindParam(':email', $nouvel_email);
        if(!$sql->execute()){
            return '<p>Désolé, une erreur est survenue. Veuillez réessayez plus tard.</p>';
        }else {
            return '<p>Votre email a bien été mise à jour !</p>';
        }
    }
    function changementEmailEmploye($prenom,$nom, $nouvel_email){
        $sql = $this->_db->prepare("UPDATE Employe SET Email = :email WHERE Prenom = '$prenom' 
            AND Nom = '$nom' ");
        $sql->bindParam(':email', $nouvel_email);
        if(!$sql->execute()){
            return '<p>Désolé, une erreur est survenue. Veuillez réessayez plus tard.</p>';
        }else {
            return '<p>Votre email a bien été mise à jour !</p>';
        }
    }

    function ajouterNouvelEmail($id, $email){
        $sql = $this->_db->prepare("UPDATE Utilisateur SET Email = :email WHERE ID = '$id'");
        $sql->bindParam(":email", $email);
        if(!$sql->execute()){
            return FALSE;
        }else {
            return TRUE;
        }
    }

    function recupererInfosInscription($id){
        $sql = "SELECT TypeInscription FROM Utilisateur WHERE ID = '$id'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $resultats = $stmt->fetch();
            $idInscription = $resultats['TypeInscription'];

        }
        $sql = "SELECT * FROM Typeinscription WHERE ID = '$idInscription'";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $infos = $stmt->fetch();
            return $infos;
        }
    }

    function ajouterLivreListeEmprunts($isbn, $id, $dateDebut){
        $sql = "INSERT INTO Emprunts (ISBN, ID_Utilisateur, Debut_Emprunt, Fin_Emprunt)
         VALUES ('$isbn', '$id', '$dateDebut', DATE_ADD('$dateDebut', INTERVAL 2 WEEK))";
         if(!$stmt = $this->_db->query($sql)){
             return FALSE;
         }else {
             return TRUE;
         }
    }

    function recupererInfosEmprunts($id){
        $sql = "SELECT * FROM Emprunts JOIN Livre WHERE ID_Utilisateur = '$id' AND Emprunts.ISBN = Livre.ISBN 
        AND Emprunts.DateRendu IS NULL";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->execute();
            $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $emprunts;
        }else{
            return "Désolé, nous n'avons pas pu récuperer vos emprunts. Réessayez plus tard!";
        }
    }

    function ajoutPenalites($id, $joursRetard){
        if($joursRetard <= 7){
            $idPenalite = 1;
        }else if($joursRetard <= 14){
            $idPenalite = 2;
        }else{
            $idPenalite = 3;
        }
        $sql = $this->_db->prepare("UPDATE Emprunts SET ID_Penalites = '$idPenalite' WHERE ID = '$id' ");
        if(!$sql->execute()){
            var_dump('Une erreur est survenue pendant le calcul des pénalités.');
        }
    }

    function recupererTypesInscription(){
        $sql = $this->_db->prepare("SELECT * FROM Typeinscription");
        if(!$sql->execute()){
            return "Impossible de récupérer les informations d'inscription.";
        }else{
            $types = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $types;
        }
    }

    function creerNouvelUtilisateur($prenom, $nom, $date_naissance, $adresse, $code_postal, $ville, $type){
        if($type == 6){
            $sql = $this->_db->prepare("INSERT INTO Employe (Prenom, Nom, DateNaissance, Adresse, CodePostal, Ville)
                VALUES (:prenom, :nom, :date_naissance, :adresse, :code_postal, :ville)");
            $sql->bindParam(":prenom", $prenom);
            $sql->bindParam(":nom", $nom);
            $sql->bindParam(":date_naissance", $date_naissance);
            $sql->bindParam(":adresse", $adresse);
            $sql->bindParam(":code_postal", $code_postal);
            $sql->bindParam(":ville", $ville);

            if(!$sql->execute()){
                return "Une erreur est survenue pendant l'ajout de ce membre. Veuillez réessayer ultérieurement.";
            }else{
                return $prenom . " " . $nom ." a bien été ajouté à la base de données.";
            }
        }else{
            $sql = $this->_db->prepare("INSERT INTO Utilisateur (Prenom, Nom, DateNaissance, Adresse, CodePostal, Ville, TypeInscription)
                VALUES (:prenom, :nom, :date_naissance, :adresse, :code_postal, :ville, :type)");
            $sql->bindParam(":prenom", $prenom);
            $sql->bindParam(":nom", $nom);
            $sql->bindParam(":date_naissance", $date_naissance);
            $sql->bindParam(":adresse", $adresse);
            $sql->bindParam(":code_postal", $code_postal);
            $sql->bindParam(":ville", $ville);
            $sql->bindParam(":type", $type);

            if(!$sql->execute()){
                return "Une erreur est survenue pendant l'ajout de ce membre. Veuillez réessayer ultérieurement.";
            }else{
                return $prenom . " " . $nom ." a bien été ajouté à la base de données.";
            }

        }
    }

    function recupererMontantPenalites($id){
        $sql = $this->_db->prepare("SELECT * FROM Emprunts LEFT JOIN Penalites WHERE Emprunts.ID_Utilisateur = '$id' AND ID_Penalites IS NOT NULL");
        if(!$sql->execute()){
            return "Nous n'avons pas pu calculer le montant de vos pénalités.";
        }else{
            $penalites = $sql->fetchAll();
            return $penalites;
        }
    }

    function montantTotalAPayer($id){
        
    }
}
