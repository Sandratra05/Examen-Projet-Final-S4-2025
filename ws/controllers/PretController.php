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
        PretEtatModel::setEtatPret($id, 3, date('Y-m-d H:i:s'));
        Flight::json(['success' => true, 'message' => 'Remboursement effectue']);
    }

    public static function validerPret($id){
        PretModel::genererPlanRemboursement($id);
        PretEtatModel::setEtatPret($id, 2, date('Y-m-d H:i:s'));
        Flight::json(['success' => true, 'message' => 'Validation effectue']);
    }

    public static function refuserPret($id){
        PretModel::genererPlanRemboursement($id);
        PretEtatModel::setEtatPret($id, 4, date('Y-m-d H:i:s'));
        Flight::json(['success' => true, 'message' => 'Validation rejete']);
    }


    // Nouvelle méthode pour afficher le PDF d'un prêt
public static function afficherPdfPret($id)
{
    try {
        // Récupérer toutes les données du prêt
        $pretData = PretModel::getPretCompletData($id);
        
        if (!$pretData) {
            Flight::json(['success' => false, 'message' => 'Prêt non trouvé'], 404);
            return;
        }

        // Sécurisation des données
        $montant = $pretData['montant'];
        $duree = isset($pretData['duree_remboursement']) ? (int)$pretData['duree_remboursement'] : 0;
        
        
        // Sécuriser la date de prêt
        $dateBase = !empty($pretData['delai_remboursement']) ? $pretData['delai_remboursement'] : date('Y-m-d');
        $delai = date('Y-m-d', strtotime($dateBase));
        
        $tauxAnnuel = $pretData['taux'];
        $tauxAssurance = isset($pretData['taux_assurance']) ? $pretData['taux_assurance'] : 0;

        // Sécuriser la date de prêt
        $dateBase = !empty($pretData['date_pret']) ? $pretData['date_pret'] : date('Y-m-d');
        $datePret = date('Y-m-d', strtotime($dateBase));

        // Calcul annuité
        $annuite = PretModel::calculerRemboursementMensuel(
            $montant,
            $pretData['id_type_pret'],
            $duree,
            $datePret
        );

        // Calculs totaux
        $totalRembourse = $annuite * $duree;
        $totalInterets = $totalRembourse - $montant;
        $totalAssurance = ($montant * $tauxAssurance / 100) * $duree / 12;

        // Calcul date de début réelle
        $moisAjout = ($delai > 0) ? $delai : 1;
        $dateDebut = date('Y/m/d', strtotime("$dateBase +$moisAjout month"));

        // Préparer les données pour le template
        $data = [
            'nom' => $pretData['nom_client'],
            'coordonne' => $pretData['coordonnees'],
            'date' => date('d/m/Y'),
            'montant' => $montant,
            'duree' => $duree,
            'taux_interet' => $tauxAnnuel,
            'taux_assurance' => $tauxAssurance,
            'annuite' => $annuite,
            'total' => $totalRembourse,
            'total_interets' => $totalInterets,
            'total_assurance' => $totalAssurance,
            'debut' => $delai
        ];

        // Rendre le template HTML
        Flight::render('pdf-html', $data);

    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

}