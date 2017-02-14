<?php

            // Création de la Class Database
class Database {
    
    private $serveur = "localhost";
    private $nomBdd = "gsb_n2f";
    private $username = "admin_gsb";
    private $password = "azerty75";
    private $port = "3306";
    private $dbh = null;
    
            // Fonction pour se connecter a au serveur où se trouvent les données.
    public function connect(){
        $this->dbh = null;
        
        try {
            $dsn = "mysql:host=".$this->serveur.";dbname=".$this->nomBdd.";port=".$this->port;
            $this->dbh = new PDO($dsn, $this->username, $this->password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->exec("SET NAMES 'UTF8'");
            
        } catch (PDOException $exception) {
            echo "Connection error : ".$exception->getMessage();
        }
        return $this->dbh;
    }
}

