<?php include 'header.php'; ?>

<!-- Main content form container -->
<main class="form-main">
  <form class="personal-info-form" id="typepret-form" aria-label="Détails du produit financier">
    <h2 class="form-title">INSERTION Type de Prêts</h2>
    <p class="form-subtitle">
      Veuillez remplir les informations ci-dessous
    </p>
    <div class="form-divider"></div>

    <!-- Affichage des messages -->
    <div id="message-container" style="display: none;">
      <!-- Les messages seront ajoutés ici par JavaScript -->
    </div>

    <div class="form-inputs">
      <input 
        class="form-input" 
        id="nom" 
        name="nom" 
        placeholder="Nom" 
        type="text" 
        autocomplete="off" 
        required 
      />

      <input 
        class="form-input" 
        id="min" 
        name="min" 
        placeholder="Montant minimum" 
        type="number" 
        step="0.01" 
        required 
      />

      <input 
        class="form-input" 
        id="max" 
        name="max" 
        placeholder="Montant maximum" 
        type="number" 
        step="0.01" 
        required 
      />

      <input 
        class="form-input" 
        id="taux" 
        name="taux" 
        placeholder="Taux (%)" 
        type="number" 
        step="0.01" 
        required 
      />

      <div class="date-input-container">
        <input 
          class="date-input" 
          id="date" 
          name="date" 
          placeholder="Date" 
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('typepret-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');
    const messageContainer = document.getElementById('message-container');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Désactiver le bouton et afficher le loader
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline';
        
        // Masquer les messages précédents
        messageContainer.style.display = 'none';
        messageContainer.innerHTML = '';

        // Préparer les données du formulaire
        const formData = new FormData(form);

        // Envoyer la requête AJAX
        fetch('/typepret/create', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher le message de succès
                showMessage(data.message, 'success');
                
                // Vider le formulaire
                form.reset();
                
                // Remettre la date par défaut
                document.getElementById('date').value = new Date().toISOString().slice(0, 16);
                
            } else {
                // Afficher les erreurs
                showErrors(data.errors);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showErrors(['Une erreur de connexion est survenue. Veuillez réessayer.']);
        })
        .finally(() => {
            // Réactiver le bouton
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoader.style.display = 'none';
        });
    });

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        
        if (type === 'success') {
            messageDiv.className = 'success-message';
            messageDiv.style.cssText = 'background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px;';
            messageDiv.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>' + message;
        } else {
            messageDiv.className = 'error-message';
            messageDiv.style.cssText = 'background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;';
            messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>' + message;
        }
        
        messageContainer.appendChild(messageDiv);
        messageContainer.style.display = 'block';
        
        // Faire défiler vers le message
        messageContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showErrors(errors) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-messages';
        errorDiv.style.cssText = 'background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;';
        
        let errorHtml = '<i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>';
        
        if (errors.length === 1) {
            errorHtml += errors[0];
        } else {
            errorHtml += '<ul style="margin: 0; padding-left: 20px;">';
            errors.forEach(error => {
                errorHtml += '<li>' + error + '</li>';
            });
            errorHtml += '</ul>';
        }
        
        errorDiv.innerHTML = errorHtml;
        messageContainer.appendChild(errorDiv);
        messageContainer.style.display = 'block';
        
        // Faire défiler vers le message
        messageContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>

<?php include 'footer.php'; ?>