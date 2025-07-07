<?php include 'header.php'; ?>

<!-- Main content form container -->
  <main class="form-main">
    <form class="personal-info-form" aria-label="Informations personnelles">
      <h2 class="form-title">Informations personnelles</h2>
      <p class="form-subtitle">
        Veuillez saisir vos informations pour continuer la<br/>souscription
      </p>
      <div class="form-divider"></div>
      
      <div class="form-inputs">
        <input class="form-input" id="nom" name="nom" placeholder="Nom" type="text" autocomplete="off"/>
        <input class="form-input" id="prenom" name="prenom" placeholder="Prénom" type="text" autocomplete="off"/>
        <input class="form-input" id="numcompte" name="numcompte" placeholder="Numéro de compte" type="text" autocomplete="off"/>
        
        <div class="date-input-container">
          <input class="date-input" id="datenaissance" name="datenaissance" placeholder="Date de naissance" type="date"/>
          <i class="fas fa-calendar-alt date-icon" aria-hidden="true"></i>
        </div>
        
        <input class="form-input" id="numtel" name="numtel" placeholder="Numéro de téléphone" type="tel" autocomplete="off"/>
        <input class="form-input" id="email" name="email" placeholder="Adresse email" type="email" autocomplete="off"/>
        <input class="form-input" id="pays" name="pays" placeholder="Pays de résidence" type="text" autocomplete="off"/>
      </div>

      <label class="consent-label" for="consent">
        <input class="consent-checkbox" id="consent" name="consent" type="checkbox"/>
        <span>
          Acceptez-vous que ces données soient transmises de manière électronique pour permettre l'étude de votre demande?
        </span>
      </label>

      <div class="form-actions">
        <button class="form-submit-btn" type="submit">Continuer</button>
        <a class="form-cancel-link" href="#">Annuler</a>
      </div>
    </form>
  </main>

  <?php include 'footer.php'; ?>
