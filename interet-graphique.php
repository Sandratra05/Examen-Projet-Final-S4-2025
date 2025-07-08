<!-- interets-graphique.php -->
<?php include 'header.php'; ?>

<main class="form-main">
  <section>
    <h2 class="form-title">Graphique des Intérêts par Mois</h2>

    <!-- Filtres -->
    <div style="margin-bottom: 20px;">
      <label for="date-debut">Date début:</label>
      <input type="month" id="date-debut" name="date_debut" />
      
      <label for="date-fin" style="margin-left: 20px;">Date fin:</label>
      <input type="month" id="date-fin" name="date_fin" />

      <button id="btn-filter" style="margin-left:10px; padding:8px 16px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        Filtrer
      </button>
      <button id="btn-reset" style="margin-left:10px; padding:8px 16px; background:#6c757d; color:white; border:none; border-radius:4px; cursor:pointer;">
        Réinitialiser
      </button>
        </button>
        <a href="interet-list.php"><button class="btn-graph" style="margin-left:10px; padding:8px 16px; background:#e72e4b; text-decoration:none ;color:white; border:none; border-radius:4px; cursor:pointer;">
            Voir en tableau
      </button></a>
    </div>

    <!-- Graphique -->
    <div>
      <canvas id="chart-interets" width="500" height="300"></canvas>
    </div>

    <div id="message-container" style="display:none; margin-top: 10px;"></div>
  </section>
</main>
<script src="url.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="ajax.js"></script>
<script>
  let interetsChart = null;

  function fetchInteretsParMois(dateDebut = null, dateFin = null) {
    let params = "";
    if (dateDebut) params += "date_debut=" + encodeURIComponent(dateDebut);
    if (dateFin) {
      if (params) params += "&";
      params += "date_fin=" + encodeURIComponent(dateFin);
    }

    ajax("POST", "/interets/list", params, function(res) {
      console.log("Réponse interets:", res);
      const msg = document.getElementById("message-container");
      msg.style.display = "none";
      msg.innerText = "";

      if (res.success && res.data && res.data.length > 0) {
        drawChart(res.data);
      } else {
        if (interetsChart) interetsChart.destroy();
        msg.style.display = "block";
        msg.style.color = "red";
        msg.innerText = res.errors?.join(", ") || "Aucune donnée trouvée.";
      }
    });
  }

  function drawChart(data) {
    data.sort((a, b) => {
        const dateA = new Date(`${a.annee}-${a.mois.toString().padStart(2, '0')}-01`);
        const dateB = new Date(`${b.annee}-${b.mois.toString().padStart(2, '0')}-01`);
        return dateA - dateB;
    });


    const ctx = document.getElementById('chart-interets').getContext('2d');
    const labels = data.map(row => `${row.nom_mois} ${row.annee}`);
    const values = data.map(row => parseFloat(row.total_interet));

    if (interetsChart) interetsChart.destroy();

    interetsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Intérêts gagnés (Ar)',
          data: values,
          backgroundColor: '#007bff',
          borderColor: '#0056b3',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return value.toLocaleString('fr-FR') + ' Ar';
              }
            }
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: ctx => ctx.raw.toLocaleString('fr-FR') + ' Ar'
            }
          }
        }
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    const dateDebutInput = document.getElementById("date-debut");
    const dateFinInput = document.getElementById("date-fin");
    const btnFilter = document.getElementById("btn-filter");
    const btnReset = document.getElementById("btn-reset");

    fetchInteretsParMois();

    btnFilter.addEventListener("click", () => {
      fetchInteretsParMois(dateDebutInput.value, dateFinInput.value);
    });

    btnReset.addEventListener("click", () => {
      dateDebutInput.value = "";
      dateFinInput.value = "";
      fetchInteretsParMois();
    });
  });
</script>

<?php include 'footer.php'; ?>
