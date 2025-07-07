<?php
require_once __DIR__ . '/../db.php';

class FondModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM ef_etablissement_financier");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM ef_etablissement_financier WHERE id_etablissement_financier = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();

        // Vérifie si l’établissement existe déjà
        $stmt = $db->prepare("SELECT * FROM ef_etablissement_financier WHERE id_etablissement_financier = ?");
        $stmt->execute([$data->id]);
        $etab = $stmt->fetch(PDO::FETCH_ASSOC);

        $idEtab = null;
        $nouveauSolde = $data->solde;

        if ($etab) {
            // Mise à jour du solde
            $nouveauSolde += $etab['solde_etablissement'];
            $idEtab = $etab['id_etablissement_financier'];

            $updateStmt = $db->prepare("UPDATE ef_etablissement_financier SET solde_etablissement = ? WHERE id_etablissement_financier = ?");
            $updateStmt->execute([$nouveauSolde, $idEtab]);
        } else {
            // Nouvelle insertion
            $insertStmt = $db->prepare("INSERT INTO ef_etablissement_financier (nom_etablissement, solde_etablissement) VALUES (?, ?)");
            $insertStmt->execute([$data->nom, $data->solde]);
            $idEtab = $db->lastInsertId();
        }

        // Insertion dans ef_mvt_solde
        $mvtStmt = $db->prepare("
            INSERT INTO ef_mvt_solde (montant, date_mvt, id_type_transaction, id_type_mvt, id_compte)
            VALUES (?, NOW(), ?, ?, ?)
        ");
        $mvtStmt->execute([
            $data->solde,
            2, // Injection de capital
            1, // Entrée
            3  // Compte fictif ici, à adapter
        ]);

        return $idEtab;
    }


    public static function update($id, $solde) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE ef_etablissement_financier SET solde_etablissement = ? WHERE id_etablissement_financier = ?");
        $stmt->execute([
            $solde, $id
        ]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM ef_etablissement_financier WHERE id_etablissement_financier = ?");
        $stmt->execute([$id]);
    }
}
