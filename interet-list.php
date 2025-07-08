<?php include 'header.php'; ?>

<main class="form-main">
  <section>
    <h2 class="form-title">Intérêts Gagnés par Mois</h2>
    
    <!-- Filtres -->
    <div style="margin-bottom:20px;">
      <div style="display:inline-block; margin-right:20px;">
        <label for="date-debut"><b>Date Début (Mois/Année):</b></label>
        <input 
          type="month" 
          id="date-debut" 
          name="date_debut"
          style="margin-left:10px;"
        />
      </div>
      
      <div style="display:inline-block; margin-right:20px;">
        <label for="date-fin"><b>Date Fin (Mois/Année):</b></label>
        <input 
          type="month" 
          id="date-fin" 
          name="date_fin"
          style="margin-left:10px;"
        />
      </div>
      
      <button id="btn-filter" style="margin-left:10px; padding:8px 16px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        Filtrer
      </button>
      <button id="btn-reset" style="margin-left:10px; padding:8px 16px; background:#6c757d; color:white; border:none; border-radius:4px; cursor:pointer;">
        Réinitialiser
      </button>
        <a href="interet-graphique.php"><button class="btn-graph" style="margin-left:10px; padding:8px 16px; background:#e72e4b; text-decoration:none ;color:white; border:none; border-radius:4px; cursor:pointer;">
            Voir en repr&eacute;sentation graphique
      </button></a>
    </div>

    <!-- Résumé -->
    <div id="summary-container" style="background:#e7f3ff; padding:15px; border-radius:5px; margin-bottom:20px; text-align:center; display:none;">
      <h3 id="total-interets" style="margin:0; color:#0056b3;"></h3>
      <p id="periode-info"></p>
    </div>

    <div id="message-container" style="display:none;"></div>
    
    <div class="table-responsive">
      <table class="history-table" id="interets-table">
        <thead>
          <tr>
            <th>Mois</th>
            <th>Année</th>
            <th style="text-align: right;">Total Intérêts</th>
            <th style="text-align: right;">Montant Total Payé</th>
            <th style="text-align: right;">Amortissement</th>
          </tr>
        </thead>
        <tbody>
          <!-- Les lignes seront insérées ici par JS -->
        </tbody>
      </table>
    </div>
  </section>
</main>
<script src="url.js"></script>
<script src="ajax.js"></script>
<script>

function fetchInteretsParMois(dateDebut = null, dateFin = null) {
  let params = "";
  if (dateDebut) params += "date_debut=" + encodeURIComponent(dateDebut);
  if (dateFin) {
    if (params) params += "&";
    params += "date_fin=" + encodeURIComponent(dateFin);
  }

  console.log("Envoi des paramètres:", params); // Debug

  ajax("POST", "/interets/list", params, function(res) {
    console.log("Réponse reçue:", res); // Debug
    
    const tableBody = document.querySelector("#interets-table tbody");
    const msg = document.getElementById("message-container");
    const summaryContainer = document.getElementById("summary-container");
    const totalInterets = document.getElementById("total-interets");
    const periodeInfo = document.getElementById("periode-info");
    
    tableBody.innerHTML = "";
    msg.style.display = "none";
    msg.innerHTML = "";

    if (res.success && res.data && res.data.length > 0) {
      // Afficher le résumé
      summaryContainer.style.display = "block";
      totalInterets.textContent = "Total des Intérêts: " + formatNumber(res.total_interets) + " Ar";
      
      // Afficher la période si définie
      if (dateDebut || dateFin) {
        const debut = dateDebut ? formatDateMonth(dateDebut) : "Début";
        const fin = dateFin ? formatDateMonth(dateFin) : "Fin";
        periodeInfo.textContent = "Période: " + debut + " - " + fin;
        periodeInfo.style.display = "block";
      } else {
        periodeInfo.style.display = "none";
      }

      // Remplir le tableau
      res.data.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${row.nom_mois}</td>
          <td>${row.annee}</td>
          <td style="text-align:right;">${formatNumber(row.total_interet)} Ar</td>
          <td style="text-align:right;">${formatNumber(row.total_montant_paye)} Ar</td>
          <td style="text-align:right;">${formatNumber(row.total_amortissement)} Ar</td>
        `;
        tableBody.appendChild(tr);
      });
    } else {
      summaryContainer.style.display = "none";
      tableBody.innerHTML = `<tr><td colspan="7" style="text-align:center;">Aucune donnée trouvée pour la période sélectionnée.</td></tr>`;
      
      if (res.errors && res.errors.length > 0) {
        msg.innerHTML = '<div style="color:#721c24;background:#f8d7da;border:1px solid #f5c6cb;padding:10px;border-radius:4px;">❌ ' + res.errors.join('<br>') + '</div>';
        msg.style.display = "block";
      }
    }
  });
}

function formatNumber(number) {
  return parseFloat(number).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
}

function formatDateMonth(dateStr) {
  const date = new Date(dateStr + '-01');
  return (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getFullYear();
}

document.addEventListener("DOMContentLoaded", function() {
  const dateDebutInput = document.getElementById("date-debut");
  const dateFinInput = document.getElementById("date-fin");
  const btnFilter = document.getElementById("btn-filter");
  const btnReset = document.getElementById("btn-reset");
  const btnGraph = document.getElementById("btn-graph");

  // Charger les données au démarrage
  fetchInteretsParMois();

  // Filtrer
  btnFilter.addEventListener("click", function() {
    fetchInteretsParMois(dateDebutInput.value, dateFinInput.value);
  });

  // Réinitialiser
  btnReset.addEventListener("click", function() {
    dateDebutInput.value = "";
    dateFinInput.value = "";
    fetchInteretsParMois();
  });

  // Permettre la recherche en appuyant sur Entrée
  dateDebutInput.addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
      fetchInteretsParMois(dateDebutInput.value, dateFinInput.value);
    }
  });

  dateFinInput.addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
      fetchInteretsParMois(dateDebutInput.value, dateFinInput.value);
    }
  });
});
</script>

<?php include 'footer.php'; ?>