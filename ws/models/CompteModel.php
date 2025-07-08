<?php
require_once __DIR__ . '/../db.php';

class CompteModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM ef_compte");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM ef_compte WHERE id_compte = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // checkSiCompteEligiblePret(id_numero_compte)[verifier si le type de compte est eligible au pret ,

    public static function checkSiCompteEligiblePret($idCompte, $idTypePret) {
        $db = getDB(); // Connexion à la base
    
        $sql = "
            SELECT 1
            FROM ef_compte c
            JOIN ef_type_pret_compte tpc ON c.id_type_compte = tpc.id_type_compte
            WHERE c.id_compte = :idCompte AND tpc.id_type_pret = :idTypePret
            LIMIT 1
        ";
    
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':idTypePret', $idTypePret, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch() !== false; // true si trouvé, donc éligible
    }

   
    
    
    // public static function create($data) {
    //     $db = getDB();
    //     $stmt = $db->prepare("INSERT INTO ef_Compte (nom, prenom, email, age) VALUES (?, ?, ?, ?)");
    //     $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age]);
    //     return $db->lastInsertId();
    // }

    // public static function update($id, $data) {
    //     $db = getDB();
    //     $stmt = $db->prepare("UPDATE etudiant SET nom = ?, prenom = ?, email = ?, age = ? WHERE id = ?");
    //     $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age, $id]);
    // }

    // public static function delete($id) {
    //     $db = getDB();
    //     $stmt = $db->prepare("DELETE FROM etudiant WHERE id = ?");
    //     $stmt->execute([$id]);
    // }
}
