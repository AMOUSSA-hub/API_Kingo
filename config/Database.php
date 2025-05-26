<?php
class Database{
		private $config_db;
    // Connexion à la base de données
    public $connexion;

		public function __construct(){
    $this->config_db = json_decode(file_get_contents(__DIR__ .'/config.json'), true);
		}

    // getter pour la connexion
    public function getConnection($uid){

        $this->connexion = null;

        try{
            $this->connexion = new PDO("mysql:host=" . $this->config_db['host'] . ";dbname=" .  $this->config_db['db'],  $this->config_db['username'],  $this->config_db['password']);
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
