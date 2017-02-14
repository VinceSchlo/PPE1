<?php
require_once ('dbConfig.inc.php');
class Dao {

            // Attribut de connexion pointer le serveur SGBD et la base de données gsb_n2f.
    private $maConnexion;
    
            // Consrtucteur initialisation de la connexion a la bdd.   
    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->maConnexion = $db;
    }
    
            // Fonction pour executer une requete sql.
   public function executeRequete($sql) {

        try {
            $resu = $this->maConnexion->prepare($sql);
            $resu->execute();
            return $resu;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }  
    
            // Fonction pour se déconnecter de la bdd. 
    public  function disconnect()
    {
        $this->maConnexion = null;
    }
    
}
