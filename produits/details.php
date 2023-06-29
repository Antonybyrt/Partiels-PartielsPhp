<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET"); 
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){  
    // La bonne méthode est utilisée
    include_once '../config/Database.php';
    include_once '../models/Employee.php';
    
    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les employés
    $employee = new Employee($db);

    // On récupère l'id de l'employé à afficher
    $data = json_decode(file_get_contents("php://input"));

    // On vérifie si l'id a bien été envoyé
    if(!empty($data->id)){
        // On appelle la méthode getEmployeeDetails
        $employee->getEmployeeDetails($data->id);

        if($employee->name != null){
            // On créé un tableau pour l'employé
            $employeeArr = [
                "id" =>  $employee->id,
                "name" => $employee->name,
                "email" => $employee->email,
                "age" => $employee->age,
                "designation" => $employee->designation,
                "created" => $employee->created
            ];

            // On envoie le code réponse 200 OK
            http_response_code(200);

            // On encode en json et on envoie
            echo json_encode($employeeArr);
        } else {
            // L'employé n'existe pas
            http_response_code(404);
            echo json_encode(["message" => "L'employé n'existe pas"]);
        }
    } else {
        // Pas d'id envoyé
        http_response_code(503);
        echo json_encode(["message" => "Impossible d'afficher l'employé : aucun id envoyé"]);
    }

} else {
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
?>