<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/RemboursementModel.php';


class PretModel
{
    public static function createPret($idCompte, $idTypePret, $montant, $dureeRemboursement)
    {
        $db = getDB();

        // Insertion du prêt
        $sql = "
            INSERT INTO ef_pret (
                date_pret,
                montant,
                duree_remboursement,
                id_type_pret,
                id_compte
            ) VALUES (NOW(),?,?,?,?)
        ";


        $stmt = $db->prepare($sql);
        $stmt->execute([
            $montant,
            $dureeRemboursement,
            $idTypePret,
            $idCompte
        ]);

        return $db->lastInsertId();
    }

    public static function calculerRemboursementMensuel(
        float $montant,
        int $idTypePret,
        int $dureeRemboursement,
        ?string $dateReference = null
    ): float {
        // Validation des paramètres
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif");
        }

        if ($dureeRemboursement <= 0) {
            throw new InvalidArgumentException("La durée doit être positive");
        }

        // 1. Obtenir le taux annuel
        try {
            $dateRef = $dateReference ?: date('Y-m-d');
            $tauxAnnuel = PretModel::getTauxByPretAndDate($idTypePret, $dateRef);
        } catch (Exception $e) {
            throw new Exception("Impossible de récupérer le taux: " . $e->getMessage());
        }

        // 2. Calcul du taux mensuel
        $tauxMensuel = $tauxAnnuel / 12;

        // 3. Calcul de l'annuité constante (formule mathématique)
        try {
            // Calcul du dénominateur (1 - (1+t)^-n)
            $denominateur = 1 - pow(1 + $tauxMensuel, -$dureeRemboursement);

            // Éviter la division par zéro
            if ($denominateur <= 0) {
                throw new Exception("Calcul impossible: dénominateur nul ou négatif");
            }

            // Formule complète
            $remboursement = $montant * ($tauxMensuel / $denominateur);

            // Arrondi à 2 décimales (centimes)
            return round($remboursement, 2);
        } catch (Exception $e) {
            throw new Exception("Erreur de calcul: " . $e->getMessage());
        }
    }

    public static function getTauxByPretAndDate($idPret, $dateReference)
    {
        $db = getDB();

        // Validation du format de date
        if (!DateTime::createFromFormat('Y-m-d', $dateReference)) {
            throw new InvalidArgumentException("Format de date invalide. Utilisez 'Y-m-d'");
        }

        // Requête combinée pour meilleure performance
        $sql = "
            SELECT t.taux
            FROM ef_taux_pret t
            JOIN ef_pret p ON t.id_type_pret = p.id_type_pret
            WHERE p.id_pret = ?
            AND t.date_taux <= ?
            ORDER BY t.date_taux DESC
            LIMIT 1
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$idPret, $dateReference . ' 23:59:59']); // Jusqu'à fin de journée
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Aucun taux trouvé pour ce prêt à la date $dateReference");
            }

            return (float)$result['taux'];
        } catch (PDOException $e) {
            throw new Exception("Erreur base de données: " . $e->getMessage());
        }
    }

    public static function getListePretsAvecEtats(): array
    {
        $sql = "
        SELECT 
            p.id_pret,
            ep.nom_etat AS etat_pret,
            c.id_compte AS numero_compte,
            cl.nom_client AS client,
            p.montant,
            p.date_pret,
            p.duree_remboursement
        FROM 
            ef_pret p
        JOIN 
            ef_compte c ON p.id_compte = c.id_compte
        JOIN 
            ef_client cl ON c.id_client = cl.id_client
        JOIN 
            (SELECT 
                 pe.id_pret, 
                 pe.id_etat_pret, 
                 e.nom_etat,
                 pe.date_pret_etat,
                 ROW_NUMBER() OVER (PARTITION BY pe.id_pret ORDER BY pe.date_pret_etat DESC) as rn
             FROM 
                 ef_pret_etat pe
             JOIN 
                 ef_etat_pret e ON pe.id_etat_pret = e.id_etat_pret) ep 
            ON p.id_pret = ep.id_pret AND ep.rn = 1
        ORDER BY 
            p.date_pret DESC
        ";

        try {
            // Préparation et exécution de la requête
            $stmt = getDB()->prepare($sql);
            $stmt->execute();

            // Récupération des résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Journalisation de l'erreur (à adapter selon votre système de logs)
            error_log("Erreur lors de la récupération des prêts: " . $e->getMessage());

            // Retourne un tableau vide en cas d'erreur
            return [];
        }
    }

    public static function getDernierEtatPret(int $idPret) {
        $db = getDB();
        $sql = "
            SELECT epe.id_etat_pret, ep.nom_etat
            FROM ef_pret_etat epe
            JOIN ef_etat_pret ep ON epe.id_etat_pret = ep.id_etat_pret
            WHERE epe.id_pret = ?
            ORDER BY epe.date_pret_etat DESC
            LIMIT 1
        ";
    
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([$idPret]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Erreur getDernierEtatPret ($idPret): " . $e->getMessage());
            return null;
        }
    }

    public static function genererPlanRemboursement(int $idPret): bool
    {
        // 1. Récupérer les données complètes du prêt avec son taux
        $sqlPret = "SELECT p.montant, p.duree_remboursement, p.date_pret,
                       t.taux, tp.nom_type_pret, t.taux_assurance
                FROM ef_pret p
                JOIN ef_type_pret tp ON p.id_type_pret = tp.id_type_pret
                JOIN ef_taux_pret t ON p.id_type_pret = t.id_type_pret
                WHERE p.id_pret = :id_pret
                ORDER BY t.date_taux DESC
                LIMIT 1";

        $pret = getDB()->prepare($sqlPret);
        $pret->execute([':id_pret' => $idPret]);
        $pretData = $pret->fetch(PDO::FETCH_ASSOC);

        if (!$pretData) {
            error_log("Prêt $idPret introuvable ou taux non défini");
            return false;
        }

        $etat_pret = PretModel::getDernierEtatPret($idPret);
        if ($etat_pret['id_etat_pret'] != 1) {
            error_log("Le pret n'est pas en etat d'etre valide");
            return false;
        }

        // 2. Calcul de l'annuité constante
        $montant = (float)$pretData['montant'];
        $duree = (int)$pretData['duree_remboursement'];
        $tauxAnnuel = (float)$pretData['taux'];
        $tauxMensuel = $tauxAnnuel / 100 / 12;

        $montantAssurrance = ((float)$pretData['taux_assurance']/100/12)* $montant;

        // Cas particulier pour les prêts sans intérêts
        if ($tauxMensuel == 0) {
            $annuite = $montant / $duree;
        } else {
            $annuite = $montant * $tauxMensuel * pow(1 + $tauxMensuel, $duree)
                / (pow(1 + $tauxMensuel, $duree) - 1);
            // $annuite = $montant * (($tauxMensuel)/(1-(pow(1+$tauxMensuel,$duree))));
        }

        // 3. Génération des échéances
        $capitalRestant = $montant;
        $date = new DateTime($pretData['date_pret']); // Date de départ = date du prêt

        for ($i = 1; $i <= $duree; $i++) {
            // Calcul des composants
            $interet = $capitalRestant * $tauxMensuel;
            $amortissement = $annuite - $interet;
            $capitalRestant -= $amortissement;

            // Ajout 1 mois à la date
            $date->add(new DateInterval('P1M'));
            $dateRemboursement = $date->format('Y-m-d');

            // Insertion
            $success = RemboursementModel::insererRemboursement(
                $idPret,
                $dateRemboursement,
                round($annuite + $montantAssurrance, 2),
                round($amortissement, 2),
                round($interet, 2)
            );

            if (!$success) {
                error_log("Échec insertion échéance $i pour prêt $idPret");
                return false;
            }
        }

        return true;
    }

    public static function getPretCompletData($idPret)
    {
        $sql = "
            SELECT 
                p.id_pret,
                p.montant,
                p.duree_remboursement,
                p.date_pret,
                p.id_type_pret,
                c.id_compte,
                cl.nom_client,
                cl.coordonnees,
                tp.nom_type_pret,
                t.taux,
                COALESCE(t.taux_assurance, 0) as taux_assurance
            FROM ef_pret p
            JOIN ef_compte c ON p.id_compte = c.id_compte
            JOIN ef_client cl ON c.id_client = cl.id_client
            JOIN ef_type_pret tp ON p.id_type_pret = tp.id_type_pret
            LEFT JOIN ef_taux_pret t ON p.id_type_pret = t.id_type_pret
            WHERE p.id_pret = ?
            ORDER BY t.date_taux DESC
            LIMIT 1
        ";
        
        try {
            $stmt = getDB()->prepare($sql);
            $stmt->execute([$idPret]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("Aucun prêt trouvé avec l'ID: " . $idPret);
                return false;
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des données du prêt: " . $e->getMessage());
            return false;
        }
    }


}
