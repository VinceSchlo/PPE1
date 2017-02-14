<?php
require_once '../includes/dao.inc.php';


            // Création de la Class LigneFHF (Table lignefraishorsforfait).
class ligneFHF {
    
    private $id;
    private $mois;
    private $libelleFrais;
    private $date;
    private $montantFrais;
    private $idVisiteur;
    
    function __construct() {
        
    }

            // Etablissement des Setter et Getter
    function getId() {
        return $this->id;
    }

    function getMois() {
        return $this->mois;
    }

    function getLibelleFrais() {
        return $this->libelleFrais;
    }

    function getDate() {
        return $this->date;
    }

    function getMontantFrais() {
        return $this->montantFrais;
    }

    function getIdVisiteur() {
        return $this->idVisiteur;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setMois($mois) {
        $this->mois = $mois;
    }

    function setLibelleFrais($libelleFrais) {
        $this->libelleFrais = $libelleFrais;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setMontantFrais($montantFrais) {
        $this->montantFrais = $montantFrais;
    }

    function setIdVisiteur($idVisiteur) {
        $this->idVisiteur = $idVisiteur;
    }
    
    
            //Fonction pour Selectionner toutes les Ligne Frais Hors Forfait d'un utilisateur et d'un mois donné.
    function tousLesLfhf(){
        
        $dao=new Dao();
        $sql= "SELECT * FROM lignefraishorsforfait AS ligne
                JOIN fichefrais AS fiche 
                ON ligne.idVisiteur = fiche.idVisiteur 
                AND ligne.mois = fiche.mois
                WHERE ligne.idVisiteur='$this->idVisiteur'
                AND ligne.mois = '$this->mois'
                AND fiche.idEtat ='CR'
                ORDER BY ligne.mois asc";
        $resu= $dao->executeRequete($sql);
    
    return $resu->fetchAll(PDO::FETCH_ASSOC);    
    }
    
    
            //Fonction pour Ajouter une nouvelle Ligne Frais Hors Forfait.
    function ajoutFHF(){           
        
	$dao=new Dao();
	$sql = "INSERT INTO lignefraishorsforfait (idVisiteur, mois, libelleFrais, montantFrais, date) VALUES ('".$this->idVisiteur."',  '".$this->mois."',   '".$this->libelleFrais."',  '".$this->montantFrais."', '".$this->date."'  )";
        $dao->executeRequete($sql);
    }

    
            //Fonction pour Modifier une Ligne Frais Hors Forfait
    function updateLigneFHF(){
        
        $dao=new Dao();
        $sql = "UPDATE lignefraishorsforfait SET libelleFrais='$this->libelleFrais', montantFrais='$this->montantFrais', date ='$this->date' where libelleFrais='$this->libelleFrais' AND id='$this->id' AND mois='$this->mois'";
        $resu=$dao->executeRequete($sql);
}

    
            //Fonction pour Supprimer une Ligne Frais Hors Forfait
    function deleteLigneFHF() {
    
        $dao=new Dao();
        $sql = "DELETE FROM lignefraishorsforfait WHERE libelleFrais='$this->libelleFrais' AND idVisiteur='$this->idVisiteur' AND mois='$this->mois' AND id='$this->id'";   
        $dao->executeRequete($sql);
}
    
//FONCTION RAJOUTER

     //Fonction COMPTABLE pour Modifier une Ligne Frais Hors Forfait
    function updateLigneCFHF($modif){
        
        $dao=new Dao();
        $sql = "UPDATE lignefraishorsforfait SET libelleFrais='$modif', montantFrais='$this->montantFrais' where libelleFrais='$this->libelleFrais' AND id='$this->id'";
        $resu=$dao->executeRequete($sql);
}

        //Fonction COMPTABLE pour Modifier une date Ligne Frais Hors Forfait
    function updateLigneMoisCFHF(){
        
        $dao=new Dao();
        $sql = "UPDATE lignefraishorsforfait SET mois='$this->mois' WHERE libelleFrais='$this->libelleFrais' AND id='$this->id'";
        $resu=$dao->executeRequete($sql);
}

    function selectFhfConsultation(){
        $dao=new Dao();
        $sql= "SELECT * FROM lignefraishorsforfait AS ligne
                JOIN fichefrais AS fiche 
                ON ligne.idVisiteur = fiche.idVisiteur 
                AND ligne.mois = fiche.mois
                WHERE ligne.idVisiteur='$this->idVisiteur'
                AND ligne.mois ='$this->mois'
                AND fiche.idEtat IN ('VA', 'MP', 'RB')";
        $resu= $dao->executeRequete($sql);
    
    return $resu->fetchAll(PDO::FETCH_ASSOC);
    }
}
