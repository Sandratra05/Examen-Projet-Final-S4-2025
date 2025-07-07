<?php include 'header.php'; ?>

<main class="form-main">
  <form class="personal-info-form" id="typepret-form" aria-label="Détails du produit financier">
    <h2 class="form-title">INSERTION Type de Prêts</h2>
    <p class="form-subtitle">
      Veuillez remplir les informations ci-dessous
    </p>
    <div class="form-divider"></div>

    <!-- Messages -->
    <div id="message-container" style="display: none;"></div>

    <div class="form-inputs">
      <input class="form-input" id="nom" name="nom" placeholder="Nom" type="text" required />
      <input class="form-input" id="min" name="min" placeholder="Montant minimum" type="number" step="0.01" required />
      <input class="form-input" id="max" name="max" placeholder="Montant maximum" type="number" step="0.01" required />
      <input class="form-input" id="taux" name="taux" placeholder="Taux (%)" type="number" step="0.01" required />

      <div class="date-input-container">
        <input 
          class="date-input" 
          id="date" 
          name="date" 
          type="datetime-local" 
          required 
          value="<?= date('Y-m-d\TH:i') ?>" 
          min="<?= date('Y-m-d\TH:i') ?>" 
        />
        <i class="fas fa-calendar-alt date-icon" aria-hidden="true"></i>
      </div>
    </div>

    <div class="form-actions">
      <button class="form-submit-btn" type="submit" id="submit-btn">
        <span class="btn-text">Continuer</span>
        <span class="btn-loader" style="display: none;">
          <i class="fas fa-spinner fa-spin"></i> Traitement...
        </span>
      </button>
      <a class="form-cancel-link" href="#">Annuler</a>
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
      if (xhr.readyState === 4 && xhr.status === 200) {
        callback(JSON.parse(xhr.responseText));
      }
    };
    xhr.send(data);
  }

  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("typepret-form");
    const submitBtn = document.getElementById("submit-btn");
    const btnText = submitBtn.querySelector(".btn-text");
    const btnLoader = submitBtn.querySelector(".btn-loader");
    const messageContainer = document.getElementById("message-container");

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Loading
      submitBtn.disabled = true;
      btnText.style.display = "none";
      btnLoader.style.display = "inline";
      messageContainer.style.display = "none";
      messageContainer.innerHTML = "";

      // Récupérer les valeurs
      const nom = document.getElementById("nom").value;
      const min = document.getElementById("min").value;
      const max = document.getElementById("max").value;
      const taux = document.getElementById("taux").value;
      const date = document.getElementById("date").value;

      const data = `nom=${encodeURIComponent(nom)}&min=${min}&max=${max}&taux=${taux}&date=${encodeURIComponent(date)}`;

      // Envoi AJAX
      ajax("POST", "/typepret/create", data, (res) => {
        if (res.success) {
          showMessage(res.message, "success");
          form.reset();
          document.getElementById("date").value = new Date().toISOString().slice(0, 16);
        } else {
          showErrors(res.errors || ["Une erreur inconnue est survenue."]);
        }

        // Réactiver le bouton
        submitBtn.disabled = false;
        btnText.style.display = "inline";
        btnLoader.style.display = "none";
      });
    });

    function showMessage(message, type) {
      const div = document.createElement("div");
      div.style.cssText =
        type === "success"
          ? "background-color:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:10px;margin-bottom:20px;border-radius:4px;"
          : "background-color:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:10px;margin-bottom:20px;border-radius:4px;";
      div.innerHTML = (type === "success" ? "✅ " : "❌ ") + message;
      messageContainer.innerHTML = "";
      messageContainer.appendChild(div);
      messageContainer.style.display = "block";
      messageContainer.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }

    function showErrors(errors) {
      const div = document.createElement("div");
      div.style.cssText =
        "background-color:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:10px;margin-bottom:20px;border-radius:4px;";
      let html = "❌ ";
      if (errors.length === 1) {
        html += errors[0];
      } else {
        html += "<ul>";
        errors.forEach(err => html += `<li>${err}</li>`);
        html += "</ul>";
      }
      div.innerHTML = html;
      messageContainer.innerHTML = "";
      messageContainer.appendChild(div);
      messageContainer.style.display = "block";
      messageContainer.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
  });
</script>

<?php include 'footer.php'; ?>
