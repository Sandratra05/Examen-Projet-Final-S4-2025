<?php
include 'header.php';

$id = $_GET['id'] ?? null;
$editData = null;
if ($id) {
    require_once __DIR__ . '/ws/models/TypePretModel.php';
    // On prend la date courante pour pré-remplir avec les valeurs actuelles
    $editData = TypePretModel::read($id, date('Y-m-d H:i:s'));
}
?>

<main class="form-main">
  <form class="personal-info-form" id="typepret-form" aria-label="Détails du produit financier">
    <h2 class="form-title"><?= $id ? 'MODIFICATION Type de Prêt' : 'INSERTION Type de Prêts' ?></h2>
    <p class="form-subtitle">
      <?= $id ? 'Modifiez les informations puis validez.' : 'Veuillez remplir les informations ci-dessous' ?>
    </p>
    <div class="form-divider"></div>

    <!-- Messages -->
    <div id="message-container" style="display: none;"></div>

    <input type="hidden" name="id" id="id" value="<?= $id ? htmlspecialchars($id) : '' ?>">

    <div class="form-inputs">
      <input class="form-input" id="nom" name="nom" placeholder="Nom du type de pret" type="text" required value="<?= $editData ? htmlspecialchars($editData['nom_type_pret']) : '' ?>" <?= $id ? 'readonly' : '' ?> />
      <input class="form-input" id="min" name="min" placeholder="Montant minimum" type="number" step="0.01" required value="<?= $editData ? htmlspecialchars($editData['montant_min']) : '' ?>" />
      <input class="form-input" id="max" name="max" placeholder="Montant maximum" type="number" step="0.01" required value="<?= $editData ? htmlspecialchars($editData['montant_max']) : '' ?>" />
      <input class="form-input" id="taux" name="taux" placeholder="Taux (%)" type="number" step="0.01" required value="<?= $editData ? htmlspecialchars($editData['taux']) : '' ?>" />
      <input class="form-input" id="taux_assurance" name="taux_assurance" placeholder="Taux assurance (%)" type="number" step="0.01" required value="<?= $editData ? htmlspecialchars($editData['taux_assurance']) : '' ?>" />

      <div class="date-input-container">
        <input 
          class="date-input" 
          id="date" 
          name="date" 
          type="datetime-local" 
          required 
          value="<?= $editData ? date('Y-m-d\TH:i', strtotime($editData['date_mvt'] ?? date('Y-m-d H:i'))) : date('Y-m-d\TH:i') ?>" 
        />
        <i class="fas fa-calendar-alt date-icon" aria-hidden="true"></i>
      </div>
    </div>

    <div class="form-actions">
      <button class="form-submit-btn" type="submit" id="submit-btn">
        <span class="btn-text"><?= $id ? 'Mettre à jour' : 'Continuer' ?></span>
        <span class="btn-loader" style="display: none;">
          <i class="fas fa-spinner fa-spin"></i> Traitement...
        </span>
      </button>
      <a class="form-cancel-link" href="typepret-list.php">Annuler</a>
    </div>
  </form>
</main>

<script src="ajax.js"></script>
<script>


document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("typepret-form");
  const submitBtn = document.getElementById("submit-btn");
  const btnText = submitBtn.querySelector(".btn-text");
  const btnLoader = submitBtn.querySelector(".btn-loader");
  const messageContainer = document.getElementById("message-container");
  const isEdit = !!document.getElementById("id").value;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Loading
    submitBtn.disabled = true;
    btnText.style.display = "none";
    btnLoader.style.display = "inline";
    messageContainer.style.display = "none";
    messageContainer.innerHTML = "";

    // Récupérer les valeurs
    const id = document.getElementById("id").value;
    const nom = document.getElementById("nom").value;
    const min = document.getElementById("min").value;
    const max = document.getElementById("max").value;
    const taux = document.getElementById("taux").value;
    const date = document.getElementById("date").value;
    const taux_assurance = document.getElementById("taux_assurance").value;

    const data = `id=${encodeURIComponent(id)}&nom=${encodeURIComponent(nom)}&min=${min}&max=${max}&taux=${taux}&taux_assurance=${taux_assurance}&date=${encodeURIComponent(date)}`;

    // Envoi AJAX
    if (isEdit) {
      ajax("POST", "/typepret/update", data, (res) => {
        if (res.success) {
          showMessage(res.message, "success");
        } else {
          showErrors(res.errors || ["Une erreur inconnue est survenue."]);
        }
        submitBtn.disabled = false;
        btnText.style.display = "inline";
        btnLoader.style.display = "none";
      });
    } else {
      ajax("POST", "/typepret/create", data, (res) => {
        if (res.success) {
          showMessage(res.message, "success");
          form.reset();
          document.getElementById("date").value = new Date().toISOString().slice(0, 16);
        } else {
          showErrors(res.errors || ["Une erreur inconnue est survenue."]);
        }
        submitBtn.disabled = false;
        btnText.style.display = "inline";
        btnLoader.style.display = "none";
      });
    }
  });

  function showMessage(message, type) {
    const div = document.createElement("div");
    div.style.cssText =
      type === "success"
        ? "background-color:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:10px;margin-bottom:20px;border-radius:4px;"
        : "background-color:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:10px;margin-bottom:20px;border-radius:4px;";
    div.innerHTML = (type === "success" ? "Valider " : " Refuser ") + message;
    messageContainer.innerHTML = "";
    messageContainer.appendChild(div);
    messageContainer.style.display = "block";
    messageContainer.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  function showErrors(errors) {
    const div = document.createElement("div");
    div.style.cssText =
      "background-color:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:10px;margin-bottom:20px;border-radius:4px;";
    let html = "Refuser ";
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
