<?php

class MouvementSoldeModel
{
    public static function createMouvementSolde($montant, $idTypeTransaction, $idTypeMvt, $idCompte, $dateMvt = null)
    {
        $db = getDB();

        // Insertion du mouvement de solde
        $sql = "
        INSERT INTO ef_mvt_solde (
            montant,
            date_mvt,
            id_type_transaction,
            id_type_mvt,
            id_compte
        ) VALUES (?, ?, ?, ?, ?)
    ";

        // Si date non fournie, utilise la date/heure actuelle
        $dateValue = $dateMvt ?: date('Y-m-d H:i:s');

        $stmt = $db->prepare($sql);
        $success = $stmt->execute([
            $montant,
            $dateValue,
            $idTypeTransaction,
            $idTypeMvt,
            $idCompte
        ]);

        if ($success) {
            return $db->lastInsertId();
        } else {
            throw new Exception("Erreur lors de la création du mouvement de solde.");
        }
    }
}
