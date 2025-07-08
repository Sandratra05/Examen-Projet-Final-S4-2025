<?php include 'header.php'; ?>

<main class="form-main">
  <section>
    <h2 class="form-title">Comparaison des Prêts</h2>
    
    <!-- Sélection des prêts à comparer -->
    <div style="margin-bottom:20px;">
      <div style="display:inline-block; margin-right:20px;">
        <label for="pret-ids"><b>IDs des prêts à comparer (séparés par des virgules):</b></label>
        <input 
          type="text" 
          id="pret-ids" 
          name="pret_ids"
          placeholder="ex: 1,2,3"
          style="margin-left:10px; padding:8px; width:200px;"
        />
      </div>
      
      <button id="btn-comparer" style="margin-left:10px; padding:8px 16px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        Comparer
      </button>
      <button id="btn-reset" style="margin-left:10px; padding:8px 16px; background:#6c757d; color:white; border:none; border-radius:4px; cursor:pointer;">
        Réinitialiser
      </button>

    </div>

    <div id="message-container" style="display:none;"></div>
    
    <div class="table-responsive">
      <table class="history-table" id="comparison-table" style="display:none;">
        <thead id="table-header">
          <!-- Les en-têtes seront insérées ici par JS -->
        </thead>
        <tbody id="table-body">
          <!-- Les lignes seront insérées ici par JS -->
        </tbody>
      </table>
    </div>
  </section>
</main>

<style>
  .comparison-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .comparison-table th,
  .comparison-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
  }
  
  .comparison-table th {
    background-color: #f8f9fa;
    font-weight: bold;
  }
  
  .comparison-table tr:nth-child(even) {
    background-color: #f8f9fa;
  }
  
  .comparison-table tr:hover {
    background-color: #e9ecef;
  }
  
  .label-column {
    background-color: #e9ecef !important;
    font-weight: bold;
    width: 180px;
  }
  
  .pret-column {
    text-align: center;
    min-width: 150px;
  }
  
  .btn-valider {
    padding: 8px 16px;
    font-size: 14px;
    background-color: #28a745;
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn-valider:hover {
    background-color: #218838;
  }
  
  .btn-valider:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
  }
  
  .montant {
    font-weight: bold;
    color: #007bff;
  }
  
  .taux {
    color: #dc3545;
  }
  
  .annuite {
    font-weight: bold;
    color: #28a745;
  }
  
  .actions-row {
    background-color: #fff3cd !important;
  }
  
  .error-message {
    color: #dc3545;
    font-style: italic;
    text-align: center;
  }
  
  @media print {
    .no-print {
      display: none;
    }
  }
</style>

<script src="url.js"></script>
<script src="ajax.js"></script>
<script>

function fetchComparaisonPrets(ids) {
  if (!ids || ids.trim() === '') {
    showMessage('Veuillez saisir au moins un ID de prêt', 'error');
    return;
  }

  const params = "ids=" + encodeURIComponent(ids.trim());
  
  console.log("Envoi des paramètres:", params);

  ajax("POST", "/prets/comparaison", params, function(res) {
    console.log("Réponse reçue:", res);
    
    const table = document.getElementById("comparison-table");
    const tableHeader = document.getElementById("table-header");
    const tableBody = document.getElementById("table-body");
    const msg = document.getElementById("message-container");
    
    tableHeader.innerHTML = "";
    tableBody.innerHTML = "";
    msg.style.display = "none";
    msg.innerHTML = "";

    if (res.success && res.data && res.data.length > 0) {
      table.style.display = "table";
      
      // Créer les en-têtes
      const headerRow = document.createElement("tr");
      headerRow.innerHTML = '<th class="label-column">Critères</th>';
      
      res.data.forEach(pret => {
        headerRow.innerHTML += `<th class="pret-column">Prêt #${pret.id}</th>`;
      });
      
      tableHeader.appendChild(headerRow);
      
      // Créer les lignes de données
      const rows = [
        { label: "Client", key: "nom_client", type: "text" },
        { label: "Type de prêt", key: "nom_type_pret", type: "text" },
        { label: "Montant", key: "montant", type: "money", class: "montant" },
        { label: "Durée", key: "duree", type: "duration" },
        { label: "Taux d'intérêt", key: "taux_interet", type: "percent", class: "taux" },
        { label: "Taux d'assurance", key: "taux_assurance", type: "percent", class: "taux" },
        { label: "Date de début", key: "delai", type: "date" },
        { label: "Annuité mensuelle", key: "annuite", type: "money", class: "annuite" },
        { label: "Total à rembourser", key: "total_rembourse", type: "money" },
        { label: "Total des intérêts", key: "total_interets", type: "money" },
        { label: "Total assurance", key: "total_assurance", type: "money" }
      ];
      
      rows.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `<td class="label-column">${row.label}</td>`;
        
        res.data.forEach(pret => {
          let value = pret[row.key] || '';
          let cellClass = row.class || 'pret-column';
          
          if (pret.error && ['annuite', 'total_rembourse', 'total_interets', 'total_assurance'].includes(row.key)) {
            value = '<span class="error-message">Erreur calcul</span>';
          } else {
            switch(row.type) {
              case 'money':
                value = formatNumber(value) + ' Ar';
                break;
              case 'percent':
                value = value + '%';
                break;
              case 'duration':
                value = value + ' mois';
                break;
              case 'date':
                if (value) {
                  const date = new Date(value);
                  value = date.toLocaleDateString('fr-FR');
                }
                break;
              case 'text':
                value = escapeHtml(value);
                break;
            }
          }
          
          tr.innerHTML += `<td class="${cellClass}">${value}</td>`;
        });
        
        tableBody.appendChild(tr);
      });
      
      // Ajouter la ligne d'actions
      const actionsRow = document.createElement("tr");
      actionsRow.className = "actions-row";
      actionsRow.innerHTML = '<td class="label-column">Actions</td>';
      
      res.data.forEach(pret => {
        const disabled = pret.error ? 'disabled' : '';
        actionsRow.innerHTML += `
          <td class="pret-column">
            <button class="btn-valider" onclick="validerPret(${pret.id})" ${disabled}>
              Valider Prêt
            </button>
          </td>
        `;
      });
      
      tableBody.appendChild(actionsRow);
      
    } else {
      table.style.display = "none";
      
      let errorMsg = "Aucun prêt trouvé pour les IDs spécifiés.";
      if (res.errors && res.errors.length > 0) {
        errorMsg = res.errors.join('<br>');
      } else if (res.message) {
        errorMsg = res.message;
      }
      
      showMessage(errorMsg, 'error');
    }
  });
}

function validerPret(idPret) {
  if (confirm('Êtes-vous sûr de vouloir valider ce prêt #' + idPret + ' ?')) {
    ajax("POST", "/prets/valider/" + idPret, "", function(res) {
      if (res.success) {
        showMessage('Prêt validé avec succès !', 'success');
        // Optionnel : recharger la comparaison
        setTimeout(() => {
          const ids = document.getElementById("pret-ids").value;
          if (ids) {
            fetchComparaisonPrets(ids);
          }
        }, 1000);
      } else {
        showMessage('Erreur lors de la validation : ' + (res.message || 'Erreur inconnue'), 'error');
      }
    });
  }
}

function showMessage(message, type) {
  const msg = document.getElementById("message-container");
  const color = type === 'error' ? '#721c24' : '#155724';
  const bgColor = type === 'error' ? '#f8d7da' : '#d4edda';
  const borderColor = type === 'error' ? '#f5c6cb' : '#c3e6cb';
  const icon = type === 'error' ? '❌' : '✅';
  
  msg.innerHTML = `<div style="color:${color};background:${bgColor};border:1px solid ${borderColor};padding:10px;border-radius:4px;">${icon} ${message}</div>`;
  msg.style.display = "block";
  
  // Masquer le message après 5 secondes si c'est un succès
  if (type === 'success') {
    setTimeout(() => {
      msg.style.display = "none";
    }, 5000);
  }
}

function formatNumber(number) {
  return parseFloat(number).toLocaleString('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Charger les IDs depuis l'URL si présents
function loadFromUrl() {
  const urlParams = new URLSearchParams(window.location.search);
  const ids = urlParams.get('ids');
  if (ids) {
    document.getElementById("pret-ids").value = ids;
    fetchComparaisonPrets(ids);
  }
}

document.addEventListener("DOMContentLoaded", function() {
  const pretIdsInput = document.getElementById("pret-ids");
  const btnComparer = document.getElementById("btn-comparer");
  const btnReset = document.getElementById("btn-reset");

  // Charger depuis l'URL au démarrage
  loadFromUrl();

  // Comparer
  btnComparer.addEventListener("click", function() {
    fetchComparaisonPrets(pretIdsInput.value);
  });

  // Réinitialiser
  btnReset.addEventListener("click", function() {
    pretIdsInput.value = "";
    document.getElementById("comparison-table").style.display = "none";
    document.getElementById("message-container").style.display = "none";
  });

  // Permettre la recherche en appuyant sur Entrée
  pretIdsInput.addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
      fetchComparaisonPrets(pretIdsInput.value);
    }
  });
});
</script>

<?php include 'footer.php'; ?>