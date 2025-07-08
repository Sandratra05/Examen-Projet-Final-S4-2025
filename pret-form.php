<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Demande de prêt</title>
  <style>
    #message-container {
      margin-top: 15px;
      padding: 10px;
      border-radius: 5px;
      display: none;
    }

    #message-container.succes {
      margin-bottom: 15px;
      background-color: #d4edda;
      color: #155724;
    }

    #message-container.erreur {
      margin-bottom: 15px;
      background-color: #f8d7da;
      color: #721c24;
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>
  <main class="form-main">
    <form class="personal-info-form" id="form-pret">
      <h2 class="form-title">Effectuer une demande de pret</h2>
      <p class="form-subtitle">
        Veuillez remplir les informations ci-dessous
      </p>
      <div class="form-divider"></div>

      <div id="message-container" style="display: none;"></div>

      <div class="form-inputs">


        <input class="form-input" type="number" id="numero_compte" name="numero_compte" placeholder="Numéro de compte *" required />



        <select id="type_pret" class="form-input" name="type_pret" required>
          <option value="">Type de prêt *</option>
        </select>



        <input type="number" class="form-input" id="duree" name="duree" min="1" required placeholder="Durée de remboursement (en mois) *" required />



        <input type="number" class="form-input" id="montant" name="montant" step="0.01" placeholder="Montant du prêt *" required />

        <input type="number" class="form-input" name="delai" id="delai" placeholder="Délai de remboursement (en mois)">
      </div>
      <p style="font-size: 12px; margin-top:10px; color:#e72e4b;">(* champ obligatoire)</p>
      <div class="form-actions">
        <button class="form-submit-btn" type="submit">Soumettre la demande</button>
      </div>
    </form>


  </main>
  <script src="url.js"></script>
  <script src="ajax.js"></script>
  <script>
    function chargerTypePret() {
      ajax("GET", "/list-type-pret", null, (data) => {
        const select = document.getElementById("type_pret");
        data.forEach(e => {
          const option = document.createElement("option");
          option.value = e.id_type_pret;
          option.textContent = e.nom_type_pret || e.nom_type_pret || `Type #${e.id_type_pret}`;
          select.appendChild(option);
        });
      });
    }

    function afficherMessage(message, isSuccess) {
      const msgDiv = document.getElementById("message-container");
      msgDiv.textContent = message;
      msgDiv.className = isSuccess ? "succes" : "erreur";
      msgDiv.style.display = "block";
    }

    document.getElementById("form-pret").addEventListener("submit", ajouterPret);


    function ajouterPret(event) {
      event.preventDefault();

      const data = new URLSearchParams();
      data.append('idCompte', document.getElementById("numero_compte").value);
      data.append('idTypePret', document.getElementById("type_pret").value);
      data.append('montant', document.getElementById("montant").value);
      data.append('dureeRemboursement', document.getElementById("duree").value);
      data.append('delai', document.getElementById("delai").value);

      ajax("POST", "/prets/create", data, (response) => {
        console.log("data = " + data + "-------");

        if (response.success) {
          afficherMessage(response.message, true);
          document.getElementById("form-pret").reset();
        } else {
          afficherMessage((response.message || "Erreur inconnue"), false);
        }
      });
    }

    window.onload = () => {
      chargerTypePret();

      // 👉 Attache l'écouteur `submit` ici proprement
      document.getElementById("form-pret").addEventListener("submit", ajouterPret);
    };
  </script>
  <?php include "footer.php"; ?>

</body>

</html>