<?php
require_once __DIR__ . '/../models/InteretModel.php';
require_once __DIR__ . '/../helpers/Utils.php';



class InteretController {
    
public static function getInteretParPeriode() {
    try {
        // Log pour debug
        error_log("Méthode: " . Flight::request()->method);
        error_log("POST data: " . print_r($_POST, true));
        error_log("GET data: " . print_r($_GET, true));
        
        // Récupération des paramètres depuis POST ou GET
        $dateDebut = null;
        $dateFin = null;
        
        // Vérifier d'abord les données POST
        if (Flight::request()->method === 'POST') {
            // Essayer d'abord $_POST
            $dateDebut = $_POST['date_debut'] ?? null;
            $dateFin = $_POST['date_fin'] ?? null;
            
            // Si pas de données dans $_POST, essayer les données raw
            if (!$dateDebut && !$dateFin) {
                $rawData = file_get_contents('php://input');
                error_log("Raw data: " . $rawData);
                if ($rawData) {
                    parse_str($rawData, $parsedData);
                    $dateDebut = $parsedData['date_debut'] ?? null;
                    $dateFin = $parsedData['date_fin'] ?? null;
                }
            }
        }
        
        // Si pas de données POST, vérifier les paramètres GET
        if (!$dateDebut && !$dateFin) {
            $dateDebut = Flight::request()->data->date_debut ?? null;
            $dateFin = Flight::request()->data->date_fin ?? null;
        }
        
        error_log("Date début: " . ($dateDebut ?? 'null'));
        error_log("Date fin: " . ($dateFin ?? 'null'));
        
        // Conversion des dates si nécessaire
        if ($dateDebut) {
            try {
                $dateDebut = date('Y-m-d', strtotime($dateDebut . '-01'));
            } catch (Exception $e) {
                error_log("Erreur conversion date début: " . $e->getMessage());
            }
        }
        
        if ($dateFin) {
            try {
                $dateFin = date('Y-m-t', strtotime($dateFin . '-01')); // Dernier jour du mois
            } catch (Exception $e) {
                error_log("Erreur conversion date fin: " . $e->getMessage());
            }
        }
        
        // Récupération des données
        $interetsParMois = InteretModel::getInteretsByPeriode($dateDebut, $dateFin);
        $totalInterets = InteretModel::getTotalInteretsPeriode($dateDebut, $dateFin);
        
        // Vérification des données
        if ($interetsParMois === false) {
            throw new Exception("Erreur lors de la récupération des données");
        }
        
        // S'assurer que les données sont un tableau
        if (!is_array($interetsParMois)) {
            $interetsParMois = [];
        }
        
        // Réponse JSON avec Flight
        Flight::json([
            'success' => true,
            'data' => $interetsParMois,
            'total_interets' => $totalInterets ?? 0,
            'periode' => [
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Erreur dans getInteretParPeriode: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        // Gestion des erreurs avec Flight
        Flight::json([
            'success' => false,
            'errors' => ['Erreur serveur: ' . $e->getMessage()],
            'data' => []
        ], 500);
    }
}

    public static function getAll() {
        $fonds = FondModel::getAll();
        Flight::json($fonds);
    }

    public static function getById($id) {   
        $fond = FondModel::getById($id);
        Flight::json($fond);
    }

    // public static function create() {
    //     $data = Flight::request()->data;
        
    //     $id = FondModel::create($data);
    //     $dateFormatted = Utils::formatDate('2025-01-01');
    //     Flight::json(['message' => 'Fond inséré', 'id' => $id]);
    // }

    public static function create() {
        $data = Flight::request()->data;
        $id = FondModel::create($data);
        try {
            $data = Flight::request()->data;

            $id = FondModel::create($data); // doit renvoyer un ID ou booléen
            Flight::json(['success' => true, 'message' => 'Fond inséré', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'errors' => ['Erreur système : ' . $e->getMessage()]]);
        }
    }


    public static function update($id) {
        $data = Flight::request()->data;

        $id = 1;

        $solde = FondModel::getById($id);
        $newSolde = 0;

        if ($solde) {
            $newSolde += $solde;
        } else {
            $newSolde = $solde;
        }

        FondModel::update($id, $newSolde);
        Flight::json(['message' => 'Fond modifié']);
    }

    public static function delete($id) {
        FondModel::delete($id);
        Flight::json(['message' => 'Fond supprimé']);
    }
}
