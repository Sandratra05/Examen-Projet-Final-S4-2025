<?php
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../models/PretModel.php';
require_once __DIR__ . '/../models/MouvementSoldeModel.php';
require_once __DIR__ . '/../models/PretEtatModel.php';
require_once __DIR__ . '/../helpers/Utils.php';



class PretController
{
    public static function getAll()
    {
        $etudiants = Etudiant::getAll();
        Flight::json($etudiants);
    }

    public static function showFormPret()
    {
        Flight::render('pret-form', []);
    }
    public static function createPret()
    {
        $request = Flight::request();
        $idCompte = $_POST['idCompte'];
        $idTypePret = $_POST['idTypePret'];
        $montant = $_POST['montant'];
        $dureeRemboursement = $_POST['dureeRemboursement'];
        $delai = $_POST['delai'];

        if (!$idCompte || !$idTypePret || !$montant || !$dureeRemboursement) {
            Flight::json(['success' => false, 'message' => 'Paramètres manquants.'], 400);
            return;
        }

        if (is_numeric($delai) && (int)$delai > 0) {
            $date = new DateTime();
            $date->modify('+' . (int)$delai . ' months');
            $delai = $date->format('Y-m-d');
        } else {
            $delai = null;
        }

        try {
            $id = PretModel::createPret($idCompte, $idTypePret, $montant, $dureeRemboursement, $delai);
            PretEtatModel::setEtatPret($id, 1, date('Y-m-d H:i:s'));
            Flight::json(['success' => true, 'message' => 'Prêt créé avec succès', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public static function listePret(){
        $prets = PretModel::getListePretsAvecEtats();
        Flight::json($prets);
    }

    public static function simulerPret($id){
        PretModel::genererPlanRemboursement($id);
        PretEtatModel::setEtatPret($id,8, date('Y-m-d H:i:s'));
        Flight::json(['success' => true, 'message' => 'Remboursement effectue']);
    }



    // public static function getById($id) {
    //     $etudiant = Etudiant::getById($id);
    //     Flight::json($etudiant);
    // }

    // public static function create() {
    //     $data = Flight::request()->data;
    //     $id = Etudiant::create($data);
    //     $dateFormatted = Utils::formatDate('2025-01-01');
    //     Flight::json(['message' => 'Étudiant ajouté', 'id' => $id]);
    // }

    // public static function update($id) {
    //     $data = Flight::request()->data;
    //     Etudiant::update($id, $data);
    //     Flight::json(['message' => 'Étudiant modifié']);
    // }

    // public static function delete($id) {
    //     Etudiant::delete($id);
    //     Flight::json(['message' => 'Étudiant supprimé']);
    // }
}
