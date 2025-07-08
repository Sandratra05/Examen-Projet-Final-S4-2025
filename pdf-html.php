<?php
// Données simulées
$nom = "Rasoa Lalao";
$coordonne = "032 12 345 67";
$date = date('d/m/Y');

$montant = 5000000;
$duree = 24;
$taux_interet = 85;
$taux_assurance = 12;
$annuite = 229000;
$total = $annuite * $duree;
$total_interets = 396000;
$total_assurance = 100000;
$debut = "01/10/2025";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Simulation_Pret_RasoaLalao</title>
   <link href="style-pdf.css" rel="stylesheet"/>
</head>
<body>

  <main class="main-content">
    <h2>Simulation de Prêt</h2>
    <p><strong>Date :</strong> <?= $date ?></p>

    <h3>Informations du client</h3>
    <p><strong>Nom :</strong> <?= htmlspecialchars($nom) ?></p>
    <p><strong>coordonnees :</strong> <?= htmlspecialchars($coordonne) ?></p>

    <h3>Détails du prêt</h3>
    <ul>
      <li><strong>Montant :</strong> <?= number_format($montant, 0, ',', ' ') ?> Ar</li>
      <li><strong>Durée :</strong> <?= $duree ?> mois</li>
      <li><strong>Taux d’intérêt :</strong> <?= $taux_interet ?> %</li>
      <li><strong>Taux assurance :</strong> <?= $taux_assurance?> %</li>
      <li><strong>Mensualité :</strong> <?= number_format($annuite, 0, ',', ' ') ?> Ar</li>
      <li><strong>Début remboursement :</strong> <?= $debut ?></li>
      <li><strong>Total remboursé :</strong> <?= number_format($total, 0, ',', ' ') ?> Ar</li>
    </ul>

    <h3>Résumé</h3>
    <p><strong>Total intérêts :</strong> <?= number_format($total_interets, 0, ',', ' ') ?> Ar</p>
    <p><strong>Total assurance :</strong> <?= number_format($total_assurance, 0, ',', ' ') ?> Ar</p>
    <p><strong>Coût total du prêt :</strong> <?= number_format($total - $montant, 0, ',', ' ') ?> Ar</p>

    <div class="signatures">
      <p>Signature agent EF : ____________________</p>
      <p>Signature client : ______________________</p>
    </div>

    <div class="no-print">
      <button onclick="window.print()"> Imprimer PDF</button>
    </div>
  </main>

</body>
</html>
