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
            $taux_assurance = $_POST['taux_assurance'] ?? '';

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
            $result = TypePretModel::create($nom, $min, $max, $taux, $date , $taux_assurance);

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

    public static function list() {
    header('Content-Type: application/json');
    $date = $_POST['date'] ?? date('Y-m-d H:i:s');
    try {
        $types = TypePretModel::getAll();
        $result = [];
        foreach ($types as $tp) {
            $info = TypePretModel::read($tp['id_type_pret'], str_replace('T', ' ', substr($date, 0, 16)));
            if ($info) $result[] = $info;
        }
        echo json_encode(['success' => true, 'data' => $result]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => ['Erreur système: ' . $e->getMessage()]]);
    }
}

public static function update() {
    header('Content-Type: application/json');
    try {
        $id = $_POST['id'] ?? '';
        $min = $_POST['min'] ?? '';
        $max = $_POST['max'] ?? '';
        $taux = $_POST['taux'] ?? '';
        $taux_assurance = $_POST['taux_assurance'] ?? '';
        $date = $_POST['date'] ?? '';

        $errors = [];
        if (empty($id)) $errors[] = "ID manquant";
        if (empty($min) || !is_numeric($min) || $min < 0) $errors[] = "Le montant minimum doit être un nombre positif";
        if (empty($max) || !is_numeric($max) || $max < 0) $errors[] = "Le montant maximum doit être un nombre positif";
        if (!empty($min) && !empty($max) && $min > $max) $errors[] = "Le montant minimum ne peut pas être supérieur au montant maximum";
        if (empty($taux) || !is_numeric($taux) || $taux < 0) $errors[] = "Le taux doit être un nombre positif";
        if (empty($date)) $errors[] = "La date est requise";

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }

        TypePretModel::updateParameters($id, $min, $max, $taux, $date , $taux_assurance);

        echo json_encode(['success' => true, 'message' => 'Type de prêt mis à jour avec succès !']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => ['Erreur système: ' . $e->getMessage()]]);
    }
}

    
}