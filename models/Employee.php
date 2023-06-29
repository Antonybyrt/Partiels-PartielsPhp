<?php
class Employee {
    // Connexion
    private $connexion;
    private $table = "Employee";

    // object properties
    public $id;
    public $name;
    public $email;
    public $age;
    public $designation;
    public $created;

    /**
     * Constructeur avec $db pour la connexion à la base de données
     *
     * @param $db
     */
    public function __construct($db){
        $this->connexion = $db;
    }

    /**
     * Lecture des employés
     *
     * @return void
     */
    public function lire(){
        // On écrit la requête
        $sql = "SELECT * FROM Employee";

        // On prépare la requête
        $query = $this->connexion->prepare($sql);

        // On exécute la requête
        $query->execute();

        // On retourne le résultat
        return $query;
    }

    /**
     * Afficher le détail d'un employé en lui passant l id en paramètre dans l'url
     *
     * @return void
     */
    public function getEmployeeDetails($id){

        // Ecriture de la requête SQL en y insérant le nom de la table
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
    
        // Préparation de la requête
        $query = $this->connexion->prepare($sql);
    
        // Protection contre les injections
        $id=htmlspecialchars(strip_tags($id));
    
        // Ajout des données protégées
        $query->bindParam(":id", $id);
    
        // Exécution de la requête
        if($query->execute()){
            if ($query->rowCount() > 0) {
                // On récupère les résultats
                $row = $query->fetch(PDO::FETCH_ASSOC);
    
                // On renvoie les données de l'employé
                return array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "email" => $row['email'],
                    "age" => $row['age'],
                    "designation" => $row['designation'],
                    "created" => $row['created']
                );
            } else {
                // Pas d'employé trouvé avec cet ID
                return null;
            }
        } else {
            // Une erreur est survenue lors de l'exécution de la requête
            return false;
        }
    }

    /**
     * créer un employé 
     *
     * @return void
     */
    public function createEmployee() {
        // Récupération du corps de la requête
        $data = json_decode(file_get_contents("php://input"));
    
        if(
            !empty($data->name) &&
            !empty($data->email) &&
            !empty($data->age) &&
            !empty($data->designation)
        ){
            // Écriture de la requête SQL en y insérant le nom de la table
            $sql = "INSERT INTO " . $this->table . " SET name=:name, email=:email, age=:age, designation=:designation, created=:created";
    
            // Préparation de la requête
            $query = $this->connexion->prepare($sql);
    
            // Protection contre les injections
            $this->name=htmlspecialchars(strip_tags($data->name));
            $this->email=htmlspecialchars(strip_tags($data->email));
            $this->age=htmlspecialchars(strip_tags($data->age));
            $this->designation=htmlspecialchars(strip_tags($data->designation));
            $this->created=date('Y-m-d H:i:s');
    
            // Ajout des données protégées
            $query->bindParam(":name", $this->name);
            $query->bindParam(":email", $this->email);
            $query->bindParam(":age", $this->age);
            $query->bindParam(":designation", $this->designation);
            $query->bindParam(":created", $this->created);
    
            // Exécution de la requête
            if($query->execute()){
                // L'employé a été créé
                return true;
            }
        }
        // Si les données ne sont pas complètes, on informe l'utilisateur
        return false;
    }

    /**
     * modifier un employé
     *
     * @return void
     */
    public function updateEmployee($id) {
        // Récupération du corps de la requête
        $data = json_decode(file_get_contents("php://input"));
    
        if(
            !empty($data->name) &&
            !empty($data->email) &&
            !empty($data->age) &&
            !empty($data->designation)
        ){
            // Écriture de la requête SQL en y insérant le nom de la table
            $sql = "UPDATE " . $this->table . " SET name=:name, email=:email, age=:age, designation=:designation WHERE id=:id";
    
            // Préparation de la requête
            $query = $this->connexion->prepare($sql);
    
            // Protection contre les injections
            $this->name=htmlspecialchars(strip_tags($data->name));
            $this->email=htmlspecialchars(strip_tags($data->email));
            $this->age=htmlspecialchars(strip_tags($data->age));
            $this->designation=htmlspecialchars(strip_tags($data->designation));
    
            // Ajout des données protégées
            $query->bindParam(":name", $this->name);
            $query->bindParam(":email", $this->email);
            $query->bindParam(":age", $this->age);
            $query->bindParam(":designation", $this->designation);
            $query->bindParam(":id", $id);
    
            // Exécution de la requête
            if($query->execute()){
                // L'employé a été mis à jour
                return true;
            }
        }
        // Si les données ne sont pas complètes, on informe l'utilisateur
        return false;
    }

    /**
     * Supprimer un employé
     *
     * @return void
     */
    public function deleteEmployee() {
        // Récupération du corps de la requête
        $data = json_decode(file_get_contents("php://input"));
    
        if(!empty($data->id)){
            // Écriture de la requête SQL en y insérant le nom de la table
            $sql = "DELETE FROM " . $this->table . " WHERE id=:id";
    
            // Préparation de la requête
            $query = $this->connexion->prepare($sql);
    
            // Protection contre les injections
            $this->id=htmlspecialchars(strip_tags($data->id));
    
            // Ajout des données protégées
            $query->bindParam(":id", $this->id);
    
            // Exécution de la requête
            if($query->execute()){
                // L'employé a été supprimé
                return true;
            }
        }
        // Si les données ne sont pas complètes, on informe l'utilisateur
        return false;
    }

}