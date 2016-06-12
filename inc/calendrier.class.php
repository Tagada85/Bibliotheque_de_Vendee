<?php


Class Calendrier{

	private $_db;

	function __construct($db=NULL){
		if(is_object($db)){
			$this->_db = $db;
		}else {
			$this->_db = new PDO("mysql:host=localhost;dbname=Bibliotheque", "root", "root");
			$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

	function recupererEvenementsCalendrier(){
		$sql = $this->_db->prepare('SELECT * FROM Calendrier');
		if(!$sql->execute()){
			return "Nous n'avons pas pu récupérer les évènements à venir.";
		}else{
			$calendrier = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $calendrier;
		}
	}

	function ajouterNouvelEvenement($titre, $dateDebut, $dateFin, $descriptif, $image){
		if(empty($dateFin) && empty($image)){
			$sql = $this->_db->prepare("INSERT INTO Calendrier (Titre, DateDebut, Descriptif) VALUES
			(:titre, '$dateDebut' , :descriptif)");
		}else if(empty($dateFin)){
			$sql = $this->_db->prepare("INSERT INTO Calendrier (Titre, DateDebut, Descriptif, Image) VALUES
		(:titre, '$dateDebut' , :descriptif, :image)");
		}else if(empty($image)){
			$sql = $this->_db->prepare("INSERT INTO Calendrier (Titre, DateDebut, DateFin, Descriptif) VALUE 
		(:titre, '$dateDebut' , '$dateFin' , :descriptif)");
		}else{
		$sql = $this->_db->prepare("INSERT INTO Calendrier (Titre, DateDebut, Descriptif) VALUES 
		(:titre, '$dateDebut' , '$dateFin' , :descriptif, :image)");
		}

		$sql->bindParam(':titre', $titre);
		$sql->bindParam(':descriptif', $descriptif);
		if(!empty($image)){
		$sql->bindParam(':image', $image);			
		}
		if(!$sql->execute()){
			return "Désolé, une erreur s'est produite. Veuillez réessayer plus tard.";
		}else{
			return "L'évènement a bien été ajouté à la base de données.";
		}

	}

	function supprimerEvenement($id){
		$sql = $this->_db->prepare("DELETE FROM Calendrier WHERE ID = :id");
		$sql->bindParam(':id', $id);
		if(!$sql->execute()){
			return 'Une erreur est survenue.';
		}else{
			return "L'évènement a bien été effacé.";
		}
	}
}