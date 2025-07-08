<?php
require_once __DIR__ . '/../db.php';

class InteretModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM ef_remboursement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère les intérêts gagnés par mois avec filtre de période
     * @param string $dateDebut Format YYYY-MM-DD
     * @param string $dateFin Format YYYY-MM-DD
     * @return array
     */
    public static function getInteretsByPeriode($dateDebut = null, $dateFin = null) {
        $db = getDB();
        
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
        
        $params = [];
        
        if ($dateDebut) {
            $sql .= " AND date >= :dateDebut";
            $params['dateDebut'] = $dateDebut;
        }
        
        if ($dateFin) {
            $sql .= " AND date <= :dateFin";
            $params['dateFin'] = $dateFin;
        }
        
        $sql .= " GROUP BY YEAR(date), MONTH(date)
                  ORDER BY annee DESC, mois DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère le total des intérêts pour une période donnée
     * @param string $dateDebut
     * @param string $dateFin
     * @return float
     */
    public static function getTotalInteretsPeriode($dateDebut = null, $dateFin = null) {
        $db = getDB();
        
        $sql = "SELECT SUM(interet) as total FROM ef_remboursement WHERE 1=1";
        $params = [];
        
        if ($dateDebut) {
            $sql .= " AND date >= :dateDebut";
            $params['dateDebut'] = $dateDebut;
        }
        
        if ($dateFin) {
            $sql .= " AND date <= :dateFin";
            $params['dateFin'] = $dateFin;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    /**
     * Récupère les détails des remboursements pour un mois donné
     * @param int $annee
     * @param int $mois
     * @return array
     */
    public static function getDetailsByMois($annee, $mois) {
        $db = getDB();
        
        $sql = "SELECT r.*, p.* 
                FROM ef_remboursement r
                LEFT JOIN ef_pret p ON r.id_pret = p.id_pret
                WHERE YEAR(r.date) = :annee AND MONTH(r.date) = :mois
                ORDER BY r.date DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(['annee' => $annee, 'mois' => $mois]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}