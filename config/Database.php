<?php
class Database{
    // Connexion à la base de données
    private $host = "dwarves.iut-fbleau.fr";
    private $db_name = "amoussa";
    private $username = "amoussa";
    private $password = "badbodhurul";
    public $connexion;

    // getter pour la connexion
    public function getConnection($uid){

        $this->connexion = null;

        try{
            $this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->connexion->exec("set names utf8");
            
           if($uid!= 0){
                    if($this -> checkToken($uid) != 1 ){ 
                        throw new Exception("Token invalide", 1);
                        
                    }
            }
          
            
        }catch(PDOException $exception){
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->connexion;
    }   


    public function checkToken($uid){

        $sql = "SELECT * FROM KingoUsers Where uid = ? LIMIT 0,1";
        
        // On prépare la requête
        $query = $this->connexion->prepare( $sql );

        //on se prémunie des injections
        $uid = htmlspecialchars(strip_tags($uid));

        // On attache l'id
        $query->bindParam(1, $uid);

        $query->execute();

     return $query->rowCount() ;



    }
}