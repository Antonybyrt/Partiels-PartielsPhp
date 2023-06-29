<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); 
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){  
    // La bonne méthode est utilisée
    include_once '../config/Database.php';
    include_once '../models/Employee.php';
    
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les employés
    $employee = new Employee($db);

    // On récupère les informations envoyées
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->name) && !empty($data->email) && !empty($data->age) && !empty($data->designation) && !empty($data->created)){
        // On appelle la méthode de création
        $employee->name = $data->name;
        $employee->email = $data->email;
        $employee->age = $data->age;
        $employee->designation = $data->designation;
        $employee->created = $data->created;

        if($employee->creer()){
            // L'employé a été créé
            http_response_code(201);
            echo json_encode(["message" => "L'employé a été créé"]);
        } else {
            // La création a échoué
            http_response_code(503);
            echo json_encode(["message" => "La création de l'employé a échoué"]);
        }
    } else {
        // Pas d'informations complètes envoyées
        http_response_code(400);
        echo json_encode(["message" => "Impossible de créer l'employé : des informations sont manquantes"]);
    }
} else {
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>