<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/User.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection($_GET['uid']);

    

    // On instancie les utilisateurs
    $user = new User($db);

    
    if(!empty($_GET["uid"])){
        $user->uid = $_GET["uid"];

      
      
        // On récupère l'utilisateur
        $user->lireUn();

        // On vérifie si le utilisateur existe
        if($user->uid != null){

            $prod = [
                "uid" => $user->uid,
                "email" => $user->email,
                "username" => $user->username
            ];
            // On envoie le code réponse 200 OK
            http_response_code(200);

            // On encode en json et on envoie
            echo json_encode($prod);
        }else{
            // 404 Not found
            http_response_code(404);
         
            echo json_encode(array("message" => "L'utilisateur n'existe pas."));
        }
        
    }
}else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}


