<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Résumé de Prêt</title>
  <style>
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

    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f8f9fa;
    }
    .container {
      background: white;
      padding: 30px;
      max-width: 700px;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #34495e;
    }
    .ligne {
      margin: 10px 0;
    }
    .ligne span {
      font-weight: bold;
      display: inline-block;
      width: 180px;
    }

      /* Cacher tout ce qui est .no-print pendant l'impression */
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Détails du Prêt</h2>
    <div class="ligne"><span>Nom du client :</span> <?= htmlspecialchars($nom) ?></div>
    <div class="ligne"><span>Coordonnées :</span> <?= htmlspecialchars($coordonne) ?></div>
    <div class="ligne"><span>Date de génération :</span> <?= $date ?></div>

    <hr>

    <div class="ligne"><span>Montant du prêt :</span> <?= number_format($montant, 2, ',', ' ') ?> Ar</div>
    <div class="ligne"><span>Durée :</span> <?= $duree ?> mois</div>
    <div class="ligne"><span>Taux d'intérêt :</span> <?= $taux_interet ?>%</div>
    <div class="ligne"><span>Taux d'assurance :</span> <?= $taux_assurance ?>%</div>
    <div class="ligne"><span>Date de début :</span> <?= $debut ?></div>

    <hr>

    <div class="ligne"><span>Annuité mensuelle :</span> <?= number_format($annuite, 2, ',', ' ') ?> Ar</div>
    <div class="ligne"><span>Total à rembourser :</span> <?= number_format($total, 2, ',', ' ') ?> Ar</div>
    <div class="ligne"><span>Total des intérêts :</span> <?= number_format($total_interets, 2, ',', ' ') ?> Ar</div>
    <div class="ligne"><span>Total assurance :</span> <?= number_format($total_assurance, 2, ',', ' ') ?> Ar</div>
  
    
    <div class="signatures">
      <p>Signature agent EF : ____________________</p>
      <p>Signature client : ______________________</p>
    </div>

    <div class="no-print virement-btn">
      <button onclick="window.print()"> Imprimer PDF</button>
    </div>
  
  
  </div>

  
</body>
</html>
