<?php
class User{
    // Connexion
    private $connexion;
    private $table = "KingoUsers";

    // object properties
    public $uid;
    public $email;
    public $username;  
    

    /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }

    /**
     * Lecture de tous les utilisateurs
     *
     * @return void
     */
    public function lire(){
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table ;

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;
    }

    /**
     * Création du unom d'utilisateur par défaut
     * @return void
     */
    public function getUsername(){

        $query = $this->connexion->prepare("Select Max(rank)+1 from ". $this->table);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->username = $row["Max(rank)+1"];
        if(!empty($this->username)){
        $this->username = "user".strval($this->username) ;
        }
        else{
            $this->username = "user"."1" ;
        }
    }

    /**
     * Créer un utilisateur
     *
     * @return void
     */
    public function creer(){

        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "INSERT INTO " . $this->table . " (uid,email,username) VALUES (:uid,:email,:username)";

        // Préparation de la requête
        $query = $this->connexion->prepare($sql);

        // Protection contre les injections
        $this->uid=htmlspecialchars(strip_tags($this->uid));
        $this->email=htmlspecialchars(strip_tags($this->email));
        
        //création du nom d'utilisateur
        $this->getUsername();
        

        // Ajout des données protégées
        $query->bindParam(":uid", $this->uid);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":username", $this->username);
      
        // Exécution de la requête
        if($query->execute()){
            return true;
        }
        return false;
    }

    /**
     * Lire un utilisateur
     *
     * @return void
     */
    public function lireUn(){
        // On écrit la requête
        $sql = "SELECT * FROM " . $this->table . " Where uid = ? LIMIT 0,1";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        $this->uid = htmlspecialchars(strip_tags($this->uid));
        
        // On attache l'id
        $query->bindParam(1, $this->uid);

        // On exécute la requête
        $query->execute();

        // on récupère la ligne
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // On hydrate l'objet
        $this->uid = $row['uid'];
        $this->email = $row['email'];
        $this->username = $row['username'];
    }

    /**
     * Supprimer un utilisateur
     *
     * @return void
     */
    public function supprimer(){
        // On écrit la requête
        $sql = "DELETE FROM " . $this->table . " WHERE uid = ?";

        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        // On sécurise les données
        $this->id=htmlspecialchars(strip_tags($this->uid));

        // On attache l'id
        $query->bindParam(1, $this->uid);

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
        $sql = "UPDATE " . $this->table . " SET email = :email, username = :username WHERE uid = :uid ";
        
        // On prépare la requête
        $query = $this->connexion->prepare($sql);
        
        // On sécurise les données
        $this->uid=htmlspecialchars(strip_tags($this->uid));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->username=htmlspecialchars(strip_tags($this->username));
        
        // On attache les variables
        $query->bindParam(':uid', $this->uid);
        $query->bindParam(':email', $this->email);
        $query->bindParam(':username', $this->username);
      
        
        // On exécute
        if($query->execute()){
            return true;
        }
        
        return false;
    }

}