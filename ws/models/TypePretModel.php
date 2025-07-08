<?php
require_once __DIR__ . '/../db.php';


class TypePretModel {
    
    /**
     * Crée un nouveau type de prêt avec ses paramètres
     * @param string $text - nom du type de prêt
     * @param float $min - montant minimum
     * @param float $max - montant maximum
     * @param float $taux - taux d'intérêt
     * @param string $date - date au format 'Y-m-d H:i:s'
     * @return int - id du type de prêt créé
     */
    public static function create($text, $min, $max, $taux, $date , $taux_assurance) {
        $db = getDB();
        
        try {
            $db->beginTransaction();
            
            // 1. Créer le type de prêt
            $stmt = $db->prepare("INSERT INTO ef_type_pret (nom_type_pret) VALUES (?)");
            $stmt->execute([$text]);
            $id_type_pret = $db->lastInsertId();
            
            // 2. Ajouter le mouvement (min/max) avec la date
            $stmt = $db->prepare("INSERT INTO ef_mvt_type_pret (montant_min, montant_max, date_mvt, id_type_pret) VALUES (?, ?, ?, ? )");
            $stmt->execute([$min, $max, $date, $id_type_pret ]);
            
            // 3. Ajouter le taux avec la date
            $stmt = $db->prepare("INSERT INTO ef_taux_pret (taux, date_taux, id_type_pret , taux_assurance) VALUES (?, ?, ? , ?)");
            $stmt->execute([$taux, $date, $id_type_pret , $taux_assurance]);
            
            $db->commit();
            return $id_type_pret;
            
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
    
    /**
     * Lit les informations d'un type de prêt à une date donnée
     * Récupère les valeurs les plus récentes (les plus proches de la date vers l'avant)
     * @param int $id_type_pret - identifiant du type de prêt
     * @param string $date - date de référence au format 'Y-m-d H:i:s'
     * @return array|false - données du type de prêt ou false si non trouvé
     */
    public static function read($id_type_pret, $date) {
        $db = getDB();
        
        // Récupérer les informations de base du type de prêt
        $stmt = $db->prepare("SELECT * FROM ef_type_pret WHERE id_type_pret = ?");
        $stmt->execute([$id_type_pret]);
        $type_pret = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$type_pret) {
            return false;
        }
        
        // Récupérer le mouvement le plus récent (montant min/max) à la date donnée
        $stmt = $db->prepare("
            SELECT montant_min, montant_max, date_mvt  
            FROM ef_mvt_type_pret 
            WHERE id_type_pret = ? AND date_mvt <= ? 
            ORDER BY date_mvt DESC 
            LIMIT 1
        ");
        $stmt->execute([$id_type_pret, $date]);
        $mouvement = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Récupérer le taux le plus récent à la date donnée
        $stmt = $db->prepare("
            SELECT taux,taux_assurance ,date_taux 
            FROM ef_taux_pret 
            WHERE id_type_pret = ? AND date_taux <= ? 
            ORDER BY date_taux DESC 
            LIMIT 1
        ");
        $stmt->execute([$id_type_pret, $date]);
        $taux = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Construire le résultat
        if (empty($mouvement['date_mvt']) || empty($taux['date_taux'])) {
    $result = [
        'id_type_pret' => null,
        'nom_type_pret' => null,
        'montant_min' => null,
        'montant_max' => null,
        'date_mvt' => null,
        'taux' => null,
        'date_taux' => null,
        'taux_assurance' => null
    ];
} else {
    $result = [
        'id_type_pret' => $type_pret['id_type_pret'],
        'nom_type_pret' => $type_pret['nom_type_pret'],
        'montant_min' => $mouvement ? $mouvement['montant_min'] : null,
        'montant_max' => $mouvement ? $mouvement['montant_max'] : null,
        'date_mvt' => $mouvement ? $mouvement['date_mvt'] : null,
        'taux' => $taux ? $taux['taux'] : null,
        'date_taux' => $taux ? $taux['date_taux'] : null,
        'taux_assurance' => $taux ? $taux['taux_assurance'] : null,
    ];
}

        
        return $result;
    }
    
    /**
     * Méthode utilitaire pour obtenir tous les types de prêts
     * @return array - liste de tous les types de prêts
     */
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM ef_type_pret ORDER BY nom_type_pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Méthode utilitaire pour mettre à jour les paramètres d'un type de prêt
     * @param int $id_type_pret - identifiant du type de prêt
     * @param float $min - nouveau montant minimum
     * @param float $max - nouveau montant maximum
     * @param float $taux - nouveau taux
     * @param string $date - date de mise à jour
     */
    public static function updateParameters($id_type_pret, $min, $max, $taux, $date , $taux_assurance) {
        $db = getDB();
        
        try {
            $db->beginTransaction();
            
            // Ajouter un nouveau mouvement
            $stmt = $db->prepare("INSERT INTO ef_mvt_type_pret (montant_min, montant_max, date_mvt, id_type_pret) VALUES (?, ?, ?, ?)");
            $stmt->execute([$min, $max, $date, $id_type_pret]);
            
            // Ajouter un nouveau taux
            $stmt = $db->prepare("INSERT INTO ef_taux_pret (taux, date_taux, id_type_pret , taux_assurance ) VALUES (?, ?, ? , ?)");
            $stmt->execute([$taux, $date, $id_type_pret , $taux_assurance]);
            
            $db->commit();
            
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
?>

