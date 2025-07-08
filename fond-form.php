<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion de Fond</title>
</head>
<body>
<?php include 'header.php'; ?>

<main class="form-main">
    <form class="personal-info-form" id="fond-form" aria-label="Détails du produit financier">
        <h2 class="form-title">Insertion de fond</h2>
        <p class="form-subtitle">
        Veuillez remplir les informations ci-dessous
        </p>
        <div class="form-divider"></div>

        <!-- Messages -->
        <div id="message-container" style="display: none;"></div>

        <div class="form-inputs">
        <input class="form-input" id="id" type="hidden" value="1" />
        <input class="form-input" id="solde" name="solde" placeholder="Ex : 1 000" type="number" required />

        <!-- <div class="date-input-container">
            <input 
            class="date-input" 
            id="date" 
            name="date" 
            type="datetime-local" 
            required 
            value="<?= date('Y-m-d\TH:i') ?>" 
            min="<?= date('Y-m-d\TH:i') ?>" 
            />
            <i class="fas fa-calendar-alt date-icon" aria-hidden="true"></i> -->
        </div>
        </div>

        <div class="form-actions">
        <button class="form-submit-btn" type="submit" id="submit-btn">
            <span class="btn-text">Valider</span>
            <span class="btn-loader" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i> Traitement...
            </span>
        </button>
        <a class="form-cancel-link" href="#">Annuler</a>
        </div>
    </form>
</main>
<script src="ajax.js"></script>
<script>
    
    // document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("fond-form");

        const messageContainer = document.getElementById("message-container");

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const solde = document.getElementById("solde").value;
            console.log(solde);
            
            const id = document.getElementById("id").value;
            const data = `solde=${encodeURIComponent(solde)}&id=${encodeURIComponent(id)}`;
            
            messageContainer.style.display = "none";
            messageContainer.innerText = "";

            if (solde > 0) {
                ajax("POST", "/fonds", data, (responseText) => {
                    console.log("Réponse brute du serveur (fond-form) :", responseText); 
                    try {
                        // const response = JSON.parse(responseText);
                        console.log("Solde = " + data);
                        
                        messageContainer.style.display = "block";
                        if (responseText.success === true) {
                            messageContainer.style.color = "green";
                            messageContainer.innerText = "Fond inséré avec succès !";
                            document.getElementById("solde").value = "";
                        } else {
                            messageContainer.style.color = "red";
                            messageContainer.innerText = response.errors?.join(", ") || "Échec de l'insertion.";
                        }
                    } catch (e) {
                        messageContainer.style.display = "block";
                        messageContainer.style.color = "red";
                        messageContainer.innerText = "Erreur inattendue côté serveur.";
                    }

                    // Effacer après 5 secondes
                    setTimeout(() => {
                        messageContainer.style.display = "none";
                    }, 5000);
                });
            } else {
                messageContainer.style.display = "block";
                messageContainer.style.color = "red";
                messageContainer.innerText = "Le solde doit être supérieur à 0.";
                setTimeout(() => {
                    messageContainer.style.display = "none";
                }, 5000);
            }
        });
    // });
    
    function insererFond() {
    }
</script>

<?php include "footer.php"; ?>
</body>
</html>