<?php
require_once __DIR__ . '/../db.php';

class InteretModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM ef_remboursement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public static function getInteretsByPeriode($dateDebut = null, $dateFin = null) {
        $db = getDB();

        try {
            $sql = "
                SELECT 
                    YEAR(r.date_remboursement) AS annee,
                    MONTH(r.date_remboursement) AS mois,
                    MONTHNAME(r.date_remboursement) AS nom_mois,
                    SUM(r.interet) AS total_interet,
                    COUNT(*) AS nombre_remboursements,
                    SUM(r.montant_payer) AS total_montant_paye,
                    SUM(r.ammortisement) AS total_amortissement
                FROM ef_remboursement r
                JOIN ef_pret p ON p.id_pret = r.id_pret
                JOIN (
                    SELECT pe1.id_pret, pe1.id_etat_pret
                    FROM ef_pret_etat pe1
                    INNER JOIN (
                        SELECT id_pret, MAX(date_pret_etat) AS max_date
                        FROM ef_pret_etat
                        GROUP BY id_pret
                    ) pe2 ON pe1.id_pret = pe2.id_pret AND pe1.date_pret_etat = pe2.max_date
                    WHERE pe1.id_etat_pret = 2
                ) pe ON pe.id_pret = p.id_pret
                 WHERE 1=1
                ";

            
            $params = [];
            
            if ($dateDebut) {
                $sql .= " AND date_remboursement >= :date_debut";
                $params[':date_debut'] = $dateDebut;
            }
            
            if ($dateFin) {
                $sql .= " AND date_remboursement <= :date_fin";
                $params[':date_fin'] = $dateFin;
            }
            
            $sql .= " GROUP BY YEAR(r.date_remboursement), MONTH(r.date_remboursement), MONTHNAME(r.date_remboursement)";
            $sql .= " ORDER BY annee DESC, mois DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erreur getInteretsByPeriode: " . $e->getMessage());
            return false;
        }
    }

    public static function getTotalInteretsPeriode($dateDebut = null, $dateFin = null) {
        $db = getDB();

        try {
            $sql = "
                SELECT SUM(r.interet) as total
                FROM ef_remboursement r
                JOIN ef_pret p ON p.id_pret = r.id_pret
                JOIN ef_pret_etat pe ON pe.id_pret = p.id_pret
                WHERE pe.id_etat_pret = 2
            ";
            $params = [];
            
            if ($dateDebut) {
                $sql .= " AND date_remboursement >= :date_debut";
                $params[':date_debut'] = $dateDebut;
            }
            
            if ($dateFin) {
                $sql .= " AND date_remboursement <= :date_fin";
                $params[':date_fin'] = $dateFin;
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("Erreur getTotalInteretsPeriode: " . $e->getMessage());
            return 0;
        }
    }
}