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
                    YEAR(r.date) AS annee,
                    MONTH(r.date) AS mois,
                    MONTHNAME(r.date) AS nom_mois,
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
                GROUP BY annee, mois
                ORDER BY annee ASC, mois ASC
            ";
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
            $sql = "
                SELECT SUM(r.interet) as total
                FROM ef_remboursement r
                JOIN ef_pret p ON p.id_pret = r.id_pret
                JOIN ef_pret_etat pe ON pe.id_pret = p.id_pret
                WHERE pe.id_etat_pret = 2
            ";
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