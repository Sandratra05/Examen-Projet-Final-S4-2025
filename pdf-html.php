<?php
// Données simulées
$nom = "Rasoa Lalao";
$matricule = "CLT00123";
$tel = "032 12 345 67";
$date = date('d/m/Y');

$montant = 5000000;
$duree = 24;
$taux_interet = 0.085;
$taux_assurance = 0.012;
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
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background-color: #f9f9fb;
      margin: 0;
      padding: 0;
      color: #333;
    }

    main.main-content {
      max-width: 800px;
      margin: 40px auto;
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #0056b3;
    }

    h3 {
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
      margin-top: 30px;
      margin-bottom: 15px;
      color: #444;
    }

    ul {
      list-style: none;
      padding-left: 0;
    }

    ul li {
      margin-bottom: 8px;
    }

    p {
      margin: 8px 0;
    }

    .signatures {
      margin-top: 50px;
      display: flex;
      justify-content: space-between;
    }

    .signatures p {
      width: 45%;
    }

    .no-print {
      text-align: center;
      margin-top: 30px;
    }

    .no-print button {
      padding: 12px 25px;
      font-size: 16px;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .no-print button:hover {
      background-color: #0056b3;
    }

    @media print {
      .no-print {
        display: none !important;
      }
      body {
        background: white;
      }
      main.main-content {
        box-shadow: none;
        border: none;
      }
    }
  </style>
</head>
<body>

  <main class="main-content">
    <h2>Simulation de Prêt</h2>
    <p><strong>Date :</strong> <?= $date ?></p>

    <h3>Informations du client</h3>
    <p><strong>Nom :</strong> <?= htmlspecialchars($nom) ?></p>
    <p><strong>Matricule :</strong> <?= htmlspecialchars($matricule) ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($tel) ?></p>

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
