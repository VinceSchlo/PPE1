<?php

require_once ('../includes/dao.inc.php');


            // Création de la Class LigneFF (Table lignefraisforfait)
class LigneFF {
    
    private $idVisiteur;
    private $mois;
    private $idFraisForfait;
    private $quantite;
    
    function __construct() {
        
    }

            // Etablissement des Setter et Getter
    function getIdVisiteur() {
        return $this->idVisiteur;
    }

    function getMois() {
        return $this->mois;
    }

    function getIdFraisForfait() {
        return $this->idFraisForfait;
    }

    function getQuantite() {
        return $this->quantite;
    }


    function setIdVisiteur($idVisiteur) {
        $this->idVisiteur = $idVisiteur;
    }

    function setMois($mois) {
        $this->mois = $mois;
    }

    function setIdFraisForfait($idFraisForfait) {
        $this->idFraisForfait = $idFraisForfait;
    }

    function setQuantite($quantite) {
        $this->quantite = $quantite;
    }


            // Fonction pour Inserer une nouvelle Ligne Frais Forfait.
    function insertLFF(){
        $dao = new Dao();
        $sql = "INSERT INTO lignefraisforfait(idVisiteur, mois, idFraisForfait, quantite) VALUES('$this->idVisiteur', '$this->mois', '$this->idFraisForfait', '$this->quantite')";
        $dao->executeRequete($sql);
        
    }
    
            // Fonction pour Selectionner toute les Ligne Frais Forfait d'un visiteur et mois donné.
    function selectLFF(){
        $dao = new Dao();
        $sql = "SELECT * FROM lignefraisforfait AS ligne
                JOIN fichefrais AS fiche 
                ON ligne.idVisiteur = fiche.idVisiteur 
                AND ligne.mois = fiche.mois
                WHERE ligne.idVisiteur='$this->idVisiteur'
                AND ligne.mois = '$this->mois'
                AND fiche.idEtat ='CR'
                ORDER BY ligne.mois asc";
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
    }
    
            // Fonction pour Modifier une Ligne Frais Forfait déjà existente.
    function updateLFF(){
        $dao = new Dao();
        $sql = "UPDATE lignefraisforfait SET idFraisForfait='$this->idFraisForfait', quantite='$this->quantite' "
                . "WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois' AND idfraisforfait='$this->idFraisForfait'";
        $dao->executeRequete($sql);
    }
    
            // Fonction pour Supprimer une Ligne Frais Forfait.
    function deleteLFF(){
        $dao = new Dao();
        $sql = "DELETE FROM lignefraisforfait WHERE idFraisForfait='$this->idFraisForfait' AND quantite='$this->quantite' AND mois='$this->mois'";
        $dao->executeRequete($sql);
    }
    
    function selectLffConsultation(){
        $dao = new Dao();
        $sql = "SELECT * FROM lignefraisforfait AS ligne
                JOIN fichefrais AS fiche 
                ON ligne.idVisiteur = fiche.idVisiteur 
                AND ligne.mois = fiche.mois
                WHERE ligne.idVisiteur='$this->idVisiteur'
                AND ligne.mois ='$this->mois'
                AND fiche.idEtat IN ('VA', 'MP', 'RB')";
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
    }
    
    
}
