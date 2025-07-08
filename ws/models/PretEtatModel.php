<?php

class PretEtatModel{
    public static function setEtatPret(
        int $idPret,
        int $idEtatPret,
        ?string $dateEtat = null
    ): bool {
        $db = getDB(); // Assumer que c’est un PDO
        
        // Validation des paramètres
        if ($idPret <= 0 || $idEtatPret <= 0) {
            throw new InvalidArgumentException("Les IDs doivent être positifs");
        }
    
        // Validation du format de date si fourni
        if ($dateEtat !== null) {
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $dateEtat);
            if (!$d || $d->format('Y-m-d H:i:s') !== $dateEtat) {
                throw new InvalidArgumentException("Format de date invalide (attendu : Y-m-d H:i:s)");
            }
        }
    
        // Formatage de la date
        $dateValue = $dateEtat ?: date('Y-m-d H:i:s');
    
        // Insertion d’un nouvel état de prêt
        $sql = "
            INSERT INTO ef_pret_etat (
                id_pret,
                id_etat_pret,
                date_pret_etat
            ) VALUES (?, ?, ?)
        ";
    
        try {
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $idPret,
                $idEtatPret,
                $dateValue
            ]);
        } catch (PDOException $e) {
            error_log("Erreur état prêt - ID Pret: $idPret, Etat: $idEtatPret, Date: $dateValue - Erreur: " . $e->getMessage());
            throw $e;
        }
    }
    
}