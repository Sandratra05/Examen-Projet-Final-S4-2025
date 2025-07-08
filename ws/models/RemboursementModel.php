<?php
require_once __DIR__ . '/../db.php';

class RemboursementModel {
    public static function insererRemboursement(int $idPret, string $date, float $montantPayer, float $ammortisement, float $interet): bool
    {
        $sql = "INSERT INTO ef_remboursement 
                (id_pret, date_remboursement, montant_payer, ammortisement, interet) 
                VALUES (?, ?, ?, ?, ?)";

        $params = [
            $idPret,
            $date,
            $montantPayer,
            $ammortisement,
            $interet
        ];

        try {
            $stmt = getDB()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur insertion remboursement (ID Pret: $idPret): " . $e->getMessage());
            return false;
        }
    }
}
