<?php include 'header.php'; ?>

<!-- Main content form container -->
<main class="form-main">
  <form class="personal-info-form" aria-label="Détails du produit financier" method="post" action="#">
    <h2 class="form-title">Détails du produit financier</h2>
    <p class="form-subtitle">
      Veuillez remplir les informations ci-dessous
    </p>
    <div class="form-divider"></div>

    <div class="form-inputs">
      <input class="form-input" id="nom" name="nom" placeholder="Nom" type="text" autocomplete="off" required />

      <input class="form-input" id="min" name="min" placeholder="Montant minimum" type="number" step="0.01" required />

      <input class="form-input" id="max" name="max" placeholder="Montant maximum" type="number" step="0.01" required />

      <input class="form-input" id="taux" name="taux" placeholder="Taux (%)" type="number" step="0.01" required />

      <div class="date-input-container">
        <input class="date-input" id="date" name="date" placeholder="Date" type="datetime-local" required 
               min="<?= date('Y-m-d\TH:i') ?>" />
        <i class="fas fa-calendar-alt date-icon" aria-hidden="true"></i>
      </div>
    </div>

    <div class="form-actions">
      <button class="form-submit-btn" type="submit">Continuer</button>
      <a class="form-cancel-link" href="#">Annuler</a>
    </div>
  </form>
</main>

<?php include 'footer.php'; ?>
