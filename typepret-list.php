<?php include 'header.php'; ?>

<main class="form-main">
  <section>
    <h2 class="form-title">Liste des Types de Prêts</h2>
    <div style="margin-bottom:20px;">
      <label for="date-search"><b>Date de référence :</b></label>
      <input 
        type="datetime-local" 
        id="date-search" 
        value="<?= date('Y-m-d\TH:i') ?>" 
        style="margin-left:10px;"
      />
    </div>
    <div id="message-container" style="display:none;"></div>
    <div class="table-responsive">
      <table class="history-table" id="typepret-table">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Montant min</th>
            <th>Montant max</th>
            <th>Taux (%)</th>
            <th>Taux d'assurance (%)</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Les lignes seront insérées ici par JS -->
        </tbody>
      </table>
    </div>
  </section>
</main>

<script src="ajax.js"></script>
<script>


function fetchTypePrets(date) {
  ajax("POST", "/typepret/list", "date=" + encodeURIComponent(date), function(res) {
    const tableBody = document.querySelector("#typepret-table tbody");
    const msg = document.getElementById("message-container");
    tableBody.innerHTML = "";
    msg.style.display = "none";
    msg.innerHTML = "";

    if (res.success && res.data.length > 0) {
      res.data.forEach(tp => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${tp.nom_type_pret}</td>
          <td>${tp.montant_min !== null ? tp.montant_min : "-"}</td>
          <td>${tp.montant_max !== null ? tp.montant_max : "-"}</td>
          <td>${tp.taux !== null ? tp.taux : "-"}</td>
          <td>${tp.taux_assurance !== null ? tp.taux_assurance : "-"}</td>
          <td>${tp.date_mvt || tp.date_taux || "-"}</td>
          <td>
            <a href="typepret-form.php?id=${tp.id_type_pret}" title="Modifier">
              <i class="fas fa-edit"></i>
            </a>
          </td>
        `;
        tableBody.appendChild(tr);
      });
    } else {
      tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Aucun type de prêt trouvé à cette date.</td></tr>`;
      if (res.errors) {
        msg.innerHTML = '<div style="color:#721c24;background:#f8d7da;border:1px solid #f5c6cb;padding:10px;border-radius:4px;">❌ ' + res.errors.join('<br>') + '</div>';
        msg.style.display = "block";
      }
    }
  });
}

document.addEventListener("DOMContentLoaded", function() {
  const dateInput = document.getElementById("date-search");
  fetchTypePrets(dateInput.value);

  dateInput.addEventListener("change", function() {
    fetchTypePrets(this.value);
  });
});
</script>

<?php include 'footer.php'; ?>