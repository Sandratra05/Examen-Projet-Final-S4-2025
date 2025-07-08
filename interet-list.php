<?php
require_once __DIR__ . '/ws/models/InteretModel.php';

// Traitement des filtres
$dateDebut = $_GET['date_debut'] ?? null;
$dateFin = $_GET['date_fin'] ?? null;

// Conversion des dates si nécessaire
if ($dateDebut) {
    $dateDebut = date('Y-m-d', strtotime($dateDebut . '-01'));
}
if ($dateFin) {
    $dateFin = date('Y-m-t', strtotime($dateFin . '-01')); // Dernier jour du mois
}

// Récupération des données
$interetsParMois = InteretModel::getInteretsByPeriode($dateDebut, $dateFin);
$totalInterets = InteretModel::getTotalInteretsPeriode($dateDebut, $dateFin);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intérêts Gagnés par Mois</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .filters {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-group {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .filter-group input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 150px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-reset {
            background: #6c757d;
            margin-left: 10px;
        }
        .btn-reset:hover {
            background: #545b62;
        }
        .summary {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary h3 {
            margin: 0;
            color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .number {
            text-align: right;
        }
        .no-data {
            text-align: center;
            color: #666;
            padding: 50px;
        }
        .detail-link {
            color: #007bff;
            text-decoration: none;
        }
        .detail-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Intérêts Gagnés par Mois</h1>
        
        <!-- Filtres -->
        <div class="filters">
            <form method="GET">
                <div class="filter-group">
                    <label for="date_debut">Date Début (Mois/Année):</label>
                    <input type="month" id="date_debut" name="date_debut" 
                           value="<?php echo isset($_GET['date_debut']) ? $_GET['date_debut'] : ''; ?>">
                </div>
                
                <div class="filter-group">
                    <label for="date_fin">Date Fin (Mois/Année):</label>
                    <input type="month" id="date_fin" name="date_fin" 
                           value="<?php echo isset($_GET['date_fin']) ? $_GET['date_fin'] : ''; ?>">
                </div>
                
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn">Filtrer</button>
                    <a href="?" class="btn btn-reset">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Résumé -->
        <div class="summary">
            <h3>Total des Intérêts: <?php echo number_format($totalInterets, 2, ',', ' '); ?> €</h3>
            <?php if ($dateDebut || $dateFin): ?>
                <p>Période: 
                    <?php echo $dateDebut ? date('m/Y', strtotime($dateDebut)) : 'Début'; ?> 
                    - 
                    <?php echo $dateFin ? date('m/Y', strtotime($dateFin)) : 'Fin'; ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Tableau -->
        <?php if (!empty($interetsParMois)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Mois</th>
                        <th>Année</th>
                        <th class="number">Total Intérêts</th>
                        <th class="number">Montant Total Payé</th>
                        <th class="number">Amortissement</th>
                        <th class="number">Nb Remboursements</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($interetsParMois as $row): ?>
                        <tr>
                            <td><?php echo $row['nom_mois']; ?></td>
                            <td><?php echo $row['annee']; ?></td>
                            <td class="number"><?php echo number_format($row['total_interet'], 2, ',', ' '); ?> €</td>
                            <td class="number"><?php echo number_format($row['total_montant_paye'], 2, ',', ' '); ?> €</td>
                            <td class="number"><?php echo number_format($row['total_amortissement'], 2, ',', ' '); ?> €</td>
                            <td class="number"><?php echo $row['nombre_remboursements']; ?></td>
                            <td>
                                <a href="details_mois.php?annee=<?php echo $row['annee']; ?>&mois=<?php echo $row['mois']; ?>" 
                                   class="detail-link">Voir détails</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>Aucune donnée trouvée pour la période sélectionnée.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>