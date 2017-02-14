<?php

require_once ('../includes/dao.inc.php');


            // Création de la classe Utilisateur (Table utilisateur).
class Utilisateur {
   
    private $id;
    private $nom;
    private $prenom;
    private $login;
    private $mdp;
    private $adresse;
    private $cp;
    private $ville;
    private $dateEmbauche;
    private $idType;
    private $mois;
    private $idVisiteur;
            
    function __construct() {
        
    }
    function getMois() {
        return $this->mois;
    }
    
    function setIdVisiteur($idVisiteur) {
        $this->idVisiteur = $idVisiteur;
    }

        function setMois($mois) {
        $this->mois = $mois;
    }

    // Etablissement des Setter et Getter
    function setId($id) {
        $this->id = $id;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    function setCp($cp) {
        $this->cp = $cp;
    }

    function setVille($ville) {
        $this->ville = $ville;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    function setDateEmbauche($dateEmbauche) {
        $this->dateEmbauche = $dateEmbauche;
    }

    function setIdType($idType) {
        $this->idType = $idType;
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getAdresse() {
        return $this->adresse;
    }

    function getCp() {
        return $this->cp;
    }

    function getVille() {
        return $this->ville;
    }

    function getLogin() {
        return $this->login;
    }

    function getMdp() {
        return $this->mdp;
    }

    function getDateEmbauche() {
        return $this->dateEmbauche;
    }

    function getIdType() {
        return $this->idType;
    }

    function getIdVisiteur() {
        return $this->idVisiteur;
    }

          // Fonction pour vérifier si les identifiant existe dans la bdd.
    public function connectionUtilisateur(){
        
        $dao = new Dao();
        $user = null;
        $sql = "SELECT * FROM utilisateur WHERE login='$this->login' AND mdp='$this->mdp'";
        $resu = $dao->executeRequete($sql);
        $ligne = $resu->fetch(PDO::FETCH_ASSOC);
          
          if ($resu->rowcount() == 1){
              $user = $ligne;
          }
        
          return $user;
        
    }
    
      // Fonction pour modifier les données d'un utilisateur.
    function updateProfile(){
        $dao=new Dao();
        $sql = "UPDATE utilisateur 
             SET adresse='$this->adresse',
             ville='$this->ville',
             cp='$this->cp',
             mdp='$this->mdp' 
             where id='$this->id'";
        $resu=$dao->executeRequete($sql);
    }
     
    
    function selectLFHFC(){
        $dao = new Dao();
        $sql = "SELECT libelleFrais, ligne.mois, ligne.idVisiteur, date, montantFrais, id
                FROM lignefraishorsforfait AS ligne
                JOIN fichefrais As fiche
                WHERE ligne.mois ='$this->mois'
                AND ligne.idVisiteur = '$this->idVisiteur'
                AND idEtat = 'CL'
                GROUP BY ligne.idVisiteur";
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
    }
    
    //FONCTION RAJOUTER
    
    
    //Fonction comptable
    // Fonction pour Selectionner toute les Ligne Frais Forfait d'un visiteur et mois donné pour comptable.
    //dans la page il nous manque l'ID visiteur on fait une requéte imbriqué avec les valeurs nom et prenom qu'on connait
    function selectLFFC(){
        $dao = new Dao();
        $sql = "SELECT ligne.mois, idFraisForfait, quantite
                FROM lignefraisforfait AS ligne
                JOIN fichefrais AS fiche
                WHERE ligne.mois = '$this->mois' 
                AND ligne.idVisiteur = '$this->idVisiteur'
                AND idEtat = 'CL'
                GROUP BY idFraisForfait";
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;
    }
    
    //Fonction pour afficher les nom et prenom des utilisateurs
    //On les rècupères pour faire une liste déroulante pour le comptable
    function selectNomPrenom(){
        $dao = new Dao();
        $sql = "SELECT DISTINCT idVisiteur, nom, prenom 
                FROM utilisateur JOIN fichefrais 
                ON id = idvisiteur
                WHERE idEtat = 'CL'
                ORDER BY nom";
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;   
    }
    
    //Fonction pour afficher les nom et prenom des utilisateurs ou Idetat = VA,MP 
    //On les rècupères pour faire une liste déroulante pour le comptable ->comptableConsultation.php
    function selectNomPrenomConsulation(){
        $dao = new Dao();
        $sql = "SELECT DISTINCT idVisiteur, nom, prenom 
                FROM utilisateur JOIN fichefrais 
                ON id = idvisiteur
                WHERE idEtat IN ('VA','MP','RB')
                ORDER BY nom";
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetchall(PDO::FETCH_ASSOC);
        
        return $ligne;   
    }
    
    function selectCivil(){
        $dao = new Dao();
        
        $sql = "SELECT id, nom, prenom FROM utilisateur WHERE id ='$this->id'";
        
        $resu = $dao->executeRequete($sql);
        
        $ligne = $resu->fetch(PDO::FETCH_ASSOC);
        
        return $ligne;
                
        
    }
    
    
}

