<?php
require_once __DIR__ . '/../models/FondModel.php';
require_once __DIR__ . '/../helpers/Utils.php';



class FondController {
    public static function getForm() {
        Flight::render('fond-form');
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
