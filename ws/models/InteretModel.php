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
                $sql = "SELECT 
                    YEAR(date) as annee,
                    MONTH(date) as mois,
                    MONTHNAME(date) as nom_mois,
                    SUM(interet) as total_interet,
                    COUNT(*) as nombre_remboursements,
                    SUM(montant_payer) as total_montant_paye,
                    SUM(ammortisement) as total_amortissement
                FROM ef_remboursement 
                WHERE 1=1";
            // $sql = "
            //     SELECT 
            //         MONTH(date) as mois,
            //         YEAR(date) as annee,
            //         CASE MONTH(date)
            //             WHEN 1 THEN 'Janvier'
            //             WHEN 2 THEN 'Février'
            //             WHEN 3 THEN 'Mars'
            //             WHEN 4 THEN 'Avril'
            //             WHEN 5 THEN 'Mai'
            //             WHEN 6 THEN 'Juin'
            //             WHEN 7 THEN 'Juillet'
            //             WHEN 8 THEN 'Août'
            //             WHEN 9 THEN 'Septembre'
            //             WHEN 10 THEN 'Octobre'
            //             WHEN 11 THEN 'Novembre'
            //             WHEN 12 THEN 'Décembre'
            //         END as nom_mois,
            //         SUM(interet) as total_interet,
            //         SUM(montant_payer) as total_montant_paye,
            //         SUM(ammortisement) as total_amortissement,
            //         COUNT(*) as nombre_remboursements
            //     FROM remboursements r
            //     WHERE 1=1
            // ";
            
            $params = [];
            
            if ($dateDebut) {
                $sql .= " AND date >= :date_debut";
                $params[':date_debut'] = $dateDebut;
            }
            
            if ($dateFin) {
                $sql .= " AND date <= :date_fin";
                $params[':date_fin'] = $dateFin;
            }
            
            $sql .= " GROUP BY YEAR(date), MONTH(date)";
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
            $sql = "SELECT SUM(interet) as total FROM ef_remboursement WHERE 1=1";
            $params = [];
            
            if ($dateDebut) {
                $sql .= " AND date >= :date_debut";
                $params[':date_debut'] = $dateDebut;
            }
            
            if ($dateFin) {
                $sql .= " AND date <= :date_fin";
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