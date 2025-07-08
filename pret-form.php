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


        <input class="form-input" type="number" id="numero_compte" name="numero_compte" placeholder="Numéro de compte" required />



        <select id="type_pret" class="form-input" name="type_pret" required>
          <option value="">Type de prêt</option>
        </select>



        <input type="number" class="form-input" id="duree" name="duree" min="1" required placeholder="Durée de remboursement (en mois)" />



        <input type="number" class="form-input" id="montant" name="montant" step="0.01" placeholder="Montant du prêt" required />
      </div>

      <div class="form-actions">
        <button class="form-submit-btn" type="submit">Soumettre la demande</button>
      </div>
    </form>


  </main>

  <script>
    const apiBase = "http://localhost/ETU003197/t/Examen-Projet-Finale-S4-2025/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          let response;
          try {
            response = JSON.parse(xhr.responseText);
          } catch (e) {
            response = {
              message: "Réponse invalide du serveur."
            };
          }
          callback(response, xhr.status);
        }
      };
      xhr.send(data);
    }

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

      ajax("POST", "/prets/create", data, (response) => {
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