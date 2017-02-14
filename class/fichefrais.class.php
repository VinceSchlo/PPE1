<?php

include_once ('../includes/dao.inc.php');

        // Création de la Class Fichfrais (Table fichefrais)
class Fichefrais {
    private $idVisiteur;
    private $mois;
    private $nbJustificatifs;
    private $montantValide;
    private $dateModif;
    private $idEtat;
    private $idTypeVehicule;
    
    
    function __construct() {
        
    }
            
         // Etablissement des Setter et Getter.
    
    function getIdVisiteur() {
        return $this->idVisiteur;
    }

    function getMois() {
        return $this->mois;
    }

    function getNbJustificatifs() {
        return $this->nbJustificatifs;
    }

    function getMontantValide() {
        return $this->montantValide;
    }

    function getDateModif() {
        return $this->dateModif;
    }

    function getIdEtat() {
        return $this->idEtat;
    }

    function getIdTypeVehicule() {
        return $this->idTypeVehicule;
    }

    function setIdVisiteur($idVisiteur) {
        $this->idVisiteur = $idVisiteur;
    }

    function setMois($mois) {
        $this->mois = $mois;
    }

    function setNbJustificatifs($nbJustificatifs) {
        $this->nbJustificatifs = $nbJustificatifs;
    }

    function setMontantValide($montantValide) {
        $this->montantValide = $montantValide;
    }

    function setDateModif($dateModif) {
        $this->dateModif = $dateModif;
    }

    function setIdEtat($idEtat) {
        $this->idEtat = $idEtat;
    }

    function setIdTypeVehicule($idTypeVehicule) {
        $this->idTypeVehicule = $idTypeVehicule;
    }

            // Fonction pour vérifier si une ligne Fiche frais existe déja pour un idVisiteur et un Mois donné.
            // Si aucune ligne n'éxiste en créer une.
    function verifierFicheFrais(){
        $dao = new Dao();
        
        $sql = "SELECT * FROM fichefrais WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois'";
        $resu = $dao->executeRequete($sql);
        $resu->fetch(PDO::FETCH_ASSOC);
        
            
        if ($resu->rowcount() == 0){
            $sql = "INSERT INTO fichefrais(idVisiteur, mois, dateModif, idEtat) VALUES('$this->idVisiteur', '$this->mois', '$this->dateModif', '$this->idEtat')";
            $dao->executeRequete($sql);
        }
        
    }   
    
    
     // Fonction pour selectionner tout dans FicheFrais pour un utilisateur et un mois donné.   
    function selectionFicheFrais(){
        $dao = new Dao();
        
        $sql = "SELECT * FROM fichefrais WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois'";
        
        $dao->executeRequete($sql);
        
    }
    
            // Fonction pour pour mettre à jours le type véhicule de fiche frais
    function updateFicheFraisCV(){
        $dao = new Dao();
        $sql = "UPDATE fichefrais "
             . "SET idTypeVehicule='$this->idTypeVehicule' "
             . "WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois'";
        $dao->executeRequete($sql);
    }
        
    
    function selectTotalFrais(){
        $dao = new Dao();
        $sql = "SELECT * FROM fichefrais WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois'";
        $resu = $dao->executeRequete($sql);
        $ligne = $resu->fetch(PDO::FETCH_ASSOC);
        
        return $ligne;
        
    }
    
    function selectTotalFF(){
        $dao = new Dao();
        $sql = "SELECT totalfraisforfait FROM totalff WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois'";
        $resu = $dao->executeRequete($sql);
        $ligne = $resu->fetch(PDO::FETCH_ASSOC);
        
        return $ligne;
    }
    
    function selectTotalFHF(){
        $dao = new Dao();
        $sql = "SELECT * FROM totallfhf WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois'";
        $resu = $dao->executeRequete($sql);
        $ligne = $resu->fetch(PDO::FETCH_ASSOC);
        
        return $ligne;
    }
    
    function selectMoisFiche(){
        $dao = new Dao();
        $sql = "SELECT mois FROM fichefrais
                WHERE idVisiteur='$this->idVisiteur'
                AND idEtat IN('VA', 'MP', 'RB')";
        $resu = $dao->executeRequete($sql);
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
                   
    }
    
        //Fonction comptable pour recuperer les fiches frais de tous les utilisateurs ou idEtat = 'CL' pour formComptable.php
    function selectFicheCL(){
        $dao = new Dao();
        $sql = "SELECT idVisiteur , nom, prenom , idEtat, montantValide,mois 
                FROM fichefrais JOIN utilisateur 
                ON idVisiteur = id
                WHERE idEtat = 'CL'
                ORDER BY nom";
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
             
    }
     
     //Fonction comptable  pour changer idEtat'CL' en VA pour formComptable.php
    function updateFicheFraisVA(){
        $dao = new Dao();
        $sql = "UPDATE fichefrais 
                SET idEtat= 'VA'
                WHERE idEtat = 'CL' " ;
        $dao->executeRequete($sql);
    }
    
       //Fonction comptable pour recuperer les fiches frais de tous les utilisateurs ou idEtat = 'VA','MP' pour ComptableConsultations.php
    function selectFicheVAMP(){
        $dao = new Dao();
        $sql = "SELECT idVisiteur , nom, prenom ,montantValide, mois, idEtat 
                FROM fichefrais JOIN utilisateur 
                ON idVisiteur = id
                WHERE idEtat IN ('VA','MP') AND idVisiteur='$this->idVisiteur'
                ORDER BY mois" ;
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
             
    }
     
     //Fonction comptable  pour changer idEtat'VA' en 'MP'  pour cpmptableConsulation.php
    function updateFicheFraisVAenMP(){
        $dao = new Dao();
        
        $sql = "UPDATE fichefrais 
                SET dateModif = '$this->dateModif' ,idEtat= 'MP'
                WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois' AND idEtat= 'VA' " ;
        $dao->executeRequete($sql);
    }
    
     //Fonction comptable  pour changer idEtat'MP' en 'RB'   pour cpmptableConsulation.php
    function updateFicheFraisMPenRB(){
        $dao = new Dao();
        
        $sql = "UPDATE fichefrais 
                SET dateModif = '$this->dateModif' ,idEtat= 'RB'
                WHERE idVisiteur='$this->idVisiteur' AND mois='$this->mois' AND idEtat= 'MP' " ;
        $dao->executeRequete($sql);
    }
    
    function updateFicheFraisMontant(){
        $dao = new Dao();
        
        $sql = "UPDATE fichefrais
                SET montantValide = (SELECT montantValide
                                     FROM totalfrais
                                     WHERE idVisiteur = '$this->idVisiteur'
                                     AND mois = '$this->mois'),
                  nbJustificatifs = (SELECT nbJustificatifs
                                     FROM nbjustif
                                     WHERE  idVisiteur = '$this->idVisiteur'
                                     AND mois = '$this->mois' ),
                        dateModif = '$this->dateModif' 
                WHERE idVisiteur = '$this->idVisiteur'
                AND mois = '$this->mois' ";
        
        $dao->executeRequete($sql);               
    }
    
    function clotureFicheSaisie(){
        $dao = new Dao();
        
        $sql = "UPDATE fichefrais
                SET idEtat = 'CL'
                WHERE mois != '$this->mois'
                AND idEtat = 'CR'
                AND idVisiteur = '$this->idVisiteur'";
                
        
        $dao->executeRequete($sql);       
    }
    
    function clotureFicheDix (){
        $dao = new Dao();
        
        $sql = "UPDATE fichefrais
                SET idEtat = 'CL'
                WHERE mois != '$this->mois'
                AND idEtat = 'CR'";
        
        $dao->executeRequete($sql);
        
    }
    
    
    
    
}
