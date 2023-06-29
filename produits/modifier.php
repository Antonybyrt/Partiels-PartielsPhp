<?php

// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT"); 
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'PUT'){  
    // La bonne méthode est utilisée
    include_once '../config/Database.php';
    include_once '../models/Employee.php';
	
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les employés
    $employee = new Employee($db);

    // On récupère l'id de l'employé à modifier
    $data = json_decode(file_get_contents("php://input"));

    // On vérifie si l'id a bien été envoyé
    if(!empty($data->id)){
        // On appelle la méthode de modification
        if($employee->updateEmployee($data->id)){
            // L'employé a été mis à jour
            http_response_code(200);
            echo json_encode(["message" => "L'employé a été mis à jour"]);
        } else {
            // La mise à jour a échoué
            http_response_code(503);
            echo json_encode(["message" => "La mise à jour de l'employé a échoué"]);
        }
    } else {
        // Pas d'id envoyé
        http_response_code(503);
        echo json_encode(["message" => "Impossible de mettre à jour l'employé : aucun id envoyé"]);
    }

} else {
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

?>