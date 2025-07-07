<?php
require_once __DIR__ . '/../models/TypePretModel.php';
require_once __DIR__ . '/../helpers/Utils.php';

class TypePretController {

    public static function form() {
        Flight::render('typepret-form', []);
    }

    public static function create() {
        // Définir le header pour JSON
        header('Content-Type: application/json');
        
        try {
            // Récupération des données du formulaire
            $nom = $_POST['nom'] ?? '';
            $min = $_POST['min'] ?? '';
            $max = $_POST['max'] ?? '';
            $taux = $_POST['taux'] ?? '';
            $date = $_POST['date'] ?? '';

            // Validation des données
            $errors = [];
            
            if (empty($nom)) {
                $errors[] = "Le nom est requis";
            }
            
            if (empty($min) || !is_numeric($min) || $min < 0) {
                $errors[] = "Le montant minimum doit être un nombre positif";
            }
            
            if (empty($max) || !is_numeric($max) || $max < 0) {
                $errors[] = "Le montant maximum doit être un nombre positif";
            }
            
            if (!empty($min) && !empty($max) && $min > $max) {
                $errors[] = "Le montant minimum ne peut pas être supérieur au montant maximum";
            }
            
            if (empty($taux) || !is_numeric($taux) || $taux < 0) {
                $errors[] = "Le taux doit être un nombre positif";
            }
            
            if (empty($date)) {
                $errors[] = "La date est requise";
            }

            // S'il y a des erreurs, retourner les erreurs en JSON
            if (!empty($errors)) {
                echo json_encode([
                    'success' => false,
                    'errors' => $errors
                ]);
                return;
            }

            // Créer le type de prêt
            $result = TypePretModel::create($nom, $min, $max, $taux, $date);

            if ($result) {
                // Succès
                echo json_encode([
                    'success' => true,
                    'message' => 'Type de prêt créé avec succès !'
                ]);
            } else {
                // Erreur lors de la création
                echo json_encode([
                    'success' => false,
                    'errors' => ['Une erreur est survenue lors de la création du type de prêt']
                ]);
            }

        } catch (Exception $e) {
            // Gestion des erreurs
            echo json_encode([
                'success' => false,
                'errors' => ['Erreur système: ' . $e->getMessage()]
            ]);
        }
    }
}