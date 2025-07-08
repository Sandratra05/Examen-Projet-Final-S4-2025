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

        if (!$idCompte || !$idTypePret || !$montant || !$dureeRemboursement) {
            Flight::json(['success' => false, 'message' => 'Paramètres manquants.'], 400);
            return;
        }

        try {
            $id = PretModel::createPret($idCompte, $idTypePret, $montant, $dureeRemboursement);
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
        PretEtatModel::setEtatPret($id, 8, date('Y-m-d H:i:s'));
        Flight::json(['success' => true, 'message' => 'Remboursement effectue']);
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

            // Calculer les données pour le PDF
            $montant = $pretData['montant'];
            $duree = $pretData['duree_remboursement'];
            $tauxAnnuel = $pretData['taux'];
            $tauxAssurance = $pretData['taux_assurance'] ?? 0;
            
        $datePret = date('Y-m-d', strtotime($pretData['date_pret']));

        $annuite = PretModel::calculerRemboursementMensuel(
            $montant,
            $pretData['id_type_pret'],
    $duree,
    $datePret // ✅ Format corrigé ici
);

            
            $totalRembourse = $annuite * $duree;
            $totalInterets = $totalRembourse - $montant;
            $totalAssurance = ($montant * $tauxAssurance / 100) * $duree / 12;
            
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
                'debut' => date('Y/m/d', strtotime($pretData['date_pret'] . ' +1 month'))
            ];
            
            // Rendre le template HTML
            Flight::render('pdf-html', $data);
            
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}