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
        PretEtatModel::setEtatPret($id, 2, date('Y-m-d H:i:s'));
        Flight::json(['success' => true, 'message' => 'Remboursement effectue']);
    }

    // public static function validerPret($id){
    //     PretModel::genererPlanRemboursement($id);
    //     PretEtatModel::setEtatPret($id, 2, date('Y-m-d H:i:s'));
    //     Flight::json(['success' => true, 'message' => 'Remboursement effectue']);
    // }


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

// Ajouter cette méthode dans PretController.php

public static function comparaisonPrets()
{
    $request = Flight::request();
    $ids = $_POST['ids'] ?? '';
    
    if (empty($ids)) {
        Flight::json(['success' => false, 'message' => 'Aucun ID de prêt spécifié.'], 400);
        return;
    }
    
    // Nettoyer et valider les IDs
    $idsArray = array_map('trim', explode(',', $ids));
    $idsArray = array_filter($idsArray, function($id) {
        return is_numeric($id) && (int)$id > 0;
    });
    
    if (empty($idsArray)) {
        Flight::json(['success' => false, 'message' => 'Aucun ID valide trouvé.'], 400);
        return;
    }
    
    try {
        $prets = [];
        $errors = [];
        
        foreach ($idsArray as $id) {
            $pretData = PretModel::getPretCompletData($id);
            
            if (!$pretData) {
                $errors[] = "Prêt #$id non trouvé";
                continue;
            }
            
            // Calculer les données dérivées
            try {
                $montant = $pretData['montant'];
                $duree = isset($pretData['duree_remboursement']) ? (int)$pretData['duree_remboursement'] : 0;
                $dateBase = !empty($pretData['date_pret']) ? $pretData['date_pret'] : date('Y-m-d');
                $tauxAssurance = isset($pretData['taux_assurance']) ? $pretData['taux_assurance'] : 0;
                
                // Calculer l'annuité
                $annuite = PretModel::calculerRemboursementMensuel(
                    $montant,
                    $pretData['id_type_pret'],
                    $duree,
                    $dateBase
                );
                
                // Calculs totaux
                $totalRembourse = $annuite * $duree;
                $totalInterets = $totalRembourse - $montant;
                $totalAssurance = ($montant * $tauxAssurance / 100) * $duree / 12;
                
                // Préparer les données pour l'affichage
                $pret = [
                    'id' => $id,
                    'nom_client' => $pretData['nom_client'],
                    'nom_type_pret' => $pretData['nom_type_pret'],
                    'montant' => $montant,
                    'duree' => $duree,
                    'taux_interet' => $pretData['taux'],
                    'taux_assurance' => $tauxAssurance,
                    'delai' => $pretData['delai_remboursement'],
                    'annuite' => $annuite,
                    'total_rembourse' => $totalRembourse,
                    'total_interets' => $totalInterets,
                    'total_assurance' => $totalAssurance,
                    'error' => false
                ];
                
                $prets[] = $pret;
                
            } catch (Exception $calcException) {
                // En cas d'erreur de calcul, inclure les données de base
                $pret = [
                    'id' => $id,
                    'nom_client' => $pretData['nom_client'],
                    'nom_type_pret' => $pretData['nom_type_pret'],
                    'montant' => $pretData['montant'],
                    'duree' => $pretData['duree_remboursement'],
                    'taux_interet' => $pretData['taux'],
                    'taux_assurance' => $pretData['taux_assurance'] ?? 0,
                    'delai' => $pretData['delai_remboursement'],
                    'annuite' => 0,
                    'total_rembourse' => 0,
                    'total_interets' => 0,
                    'total_assurance' => 0,
                    'error' => true
                ];
                
                $prets[] = $pret;
                $errors[] = "Erreur de calcul pour le prêt #$id : " . $calcException->getMessage();
            }
        }
        
        if (empty($prets)) {
            Flight::json(['success' => false, 'message' => 'Aucun prêt valide trouvé.', 'errors' => $errors], 404);
            return;
        }
        
        $response = [
            'success' => true,
            'data' => $prets
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        Flight::json($response);
        
    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => 'Erreur lors de la comparaison : ' . $e->getMessage()], 500);
    }
}

public static function validerPret($id)
{
    try {
        // Vérifier si le prêt existe
        $pretData = PretModel::getPretCompletData($id);
        
        if (!$pretData) {
            Flight::json(['success' => false, 'message' => 'Prêt non trouvé'], 404);
            return;
        }
        
        // Générer le plan de remboursement
        PretModel::genererPlanRemboursement($id);
        
        // Changer l'état du prêt (par exemple, état 2 pour "validé")
        PretEtatModel::setEtatPret($id, 2, date('Y-m-d H:i:s'));
        
        Flight::json(['success' => true, 'message' => 'Prêt validé avec succès']);
        
    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => 'Erreur lors de la validation : ' . $e->getMessage()], 500);
    }
}

}