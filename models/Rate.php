<?php
class Rate{
    // Connexion
    private $connexion;
    private $table = "KingoRates";

    // object properties
    public $idRate;
    public $idDeal;
    public $idUser;
    public $rate;
    public $comment;
    public $created_at;
    

    /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }

    

    /**
     * Créer un avis
     *
     * @return void
     */
    public function creer(){

        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO " . $this->table . " (idDeal,idUser,Rate,Comment) VALUES (:idDeal,:idUser,:Rate,:Comment)";

        // Préparation de la requête
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->idDeal=htmlspecialchars(strip_tags($this->idDeal));
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));
        $this->rate=htmlspecialchars(strip_tags($this->rate));
        $this->comment=htmlspecialchars(strip_tags($this->comment));
        
       

        // Ajout des données protégées
        $query->bindParam(":idDeal", $this->idDeal);
        $query->bindParam(":idUser", $this->idUser);
        $query->bindParam(":Rate", $this->rate);
        $query->bindParam(":Comment", $this->comment);
      
        // Exécution de la requête
        if($query->execute()){
            return true;
        }
        return false;
    }

    /**
     * Lire tous les avis d'un Deal
     *
     * @return void
     */
    public function lireParDeal(){
        // On écrit la requête
        $sql = "SELECT `idRate`,username,`Rate`,`Comment`,`created_at` FROM " . $this->table . " JOIN KingoUsers on KingoUsers.uid = ". $this->table . ".idUser WHERE idDeal = ? " ;

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->idDeal=htmlspecialchars(strip_tags($this->idDeal));

        // Ajout des données protégées
        $query->bindParam(1, $this->idDeal);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;
    }


    /**
     * Lire tous les avis d'un utilisateur
     *
     * @return void
     */
    public function lireParUser(){
        // On écrit la requête
        $sql = "SELECT idRate, idDeal,Rate,Comment,created_at FROM " . $this->table." WHERE idUser = ? order by created_at DESC " ;

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->idUser=htmlspecialchars(strip_tags($this->idUser));

        // Ajout des données protégées
        $query->bindParam(1, $this->idUser);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;
    }
    /**
     * Supprimer un avis
     *
     * @return void
     */
    public function supprimer(){
        // On écrit la requête
        $sql = "DELETE FROM " . $this->table . " WHERE idRate = ?";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On sécurise les données
        $this->idRate=htmlspecialchars(strip_tags($this->idRate));

        // On attache l'id
        $query->bindParam(1, $this->idRate);

        // On exécute la requête
        if($query->execute()){
            return true;
        }
        
        return false;
    }

    /**
     * Mettre à jour un utilisateur
     *
     * @return void
     */
    public function modifier(){
        // On écrit la requête
        $sql = "UPDATE " . $this->table . " SET comment = ? WHERE idRate = ?";
        
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
        
        // On sécurise les données
        $this->idRate=htmlspecialchars(strip_tags($this->idRate));
        $this->comment=htmlspecialchars(strip_tags($this->comment));
        
        
        
        // On attache les variables
        $query->bindParam(1, $this->comment);
        $query->bindParam(2, $this->idRate);
      
        
        // On exécute
        if($query->execute()){
            return true;
        }
        
        return false;
    }

}