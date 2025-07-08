<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Prêts</title>
    <style>
        .btn-simuler {
            background-color: #2ecc71;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .btn-refuser {
            background-color: #e74c3c;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .btn-valider {
            background-color: #3498db;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .btn-pdf {
            background-color: #9b59b6;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .btn-simuler:hover {
            background-color: #27ae60;
        }

        .btn-refuser:hover {
            background-color: #c0392b;
        }

        .btn-valider:hover {
            background-color: #2980b9;
        }

        .btn-pdf:hover {
            background-color: #8e44ad;
        }

        .etat-attente {
            color: #f39c12;
            font-weight: bold;
        }

        .etat-approuve {
            color: #2ecc71;
            font-weight: bold;
        }

        .etat-rejete {
            color: #e74c3c;
            font-weight: bold;
        }

        .etat-cloture {
            color: #9b59b6;
            font-weight: bold;
        }

        .actions-cell {
            white-space: nowrap;
            min-width: 200px;
        }

        .checkbox-cell {
            text-align: center;
            width: 50px;
        }

        .checkbox-cell input[type="checkbox"] {
            transform: scale(1.2);
        }
    </style>
</head>
<?php include 'header.php'; ?>
<style>
    .action {
        background-color: #e72e4b;
        color: white;
        font-size: 12px;
        font-weight: 700;
        border-radius: 9999px;
        padding: 8px 24px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .filter-container {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .filter-container label {
        font-weight: bold;
    }

    .filter-container input,
    .filter-container select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .filter-container select {
        background-color: white;
    }
</style>

<body>
    <main class="form-main">
        <section>
            <h2 class="form-title">Liste des Demandes de Prêt</h2>

            <div class="filter-container">
                <label for="date-debut">Date début :</label>
                <input type="datetime-local" id="date-debut" value="">

                <label for="date-fin">Date fin :</label>
                <input type="datetime-local" id="date-fin" value="">

                <label for="etat-filtre">État :</label>
                <select id="etat-filtre">
                    <option value="">-- Tous --</option>
                    <option value="En attente">En attente</option>
                    <option value="Simulé">Simulé</option>
                    <option value="Approuvé">Approuvé</option>
                    <option value="Rejeté">Rejeté</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="history-table" id="listePret">
                    <thead>
                        <tr>
                            <th>ID Prêt</th>
                            <th>État</th>
                            <th>Numéro de Compte</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Date de prêt</th>
                            <th>Durée remboursement (mois)</th>
                            <th>Actions</th>
                            <th>Cocher</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="url.js"></script>

    <script>
        // const apiBase = "http://localhost/ETU003197/t/Examen-Projet-Finale-S4-2025/ws";

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

        function simulerPret(id) {
            if (id) {
                if (confirm("Êtes-vous sûr de vouloir simuler ce prêt ?")) {
                    ajax("GET", `/prets/simulation/${id}`, null, (response, status) => {
                        if (status === 200 && response.success) {
                            alert(response.message);
                            chargerPret(); // recharge la liste
                        } else {
                            alert("Erreur : " + (response.message || "Échec de simulation."));
                        }
                    });
                }
            }
        }

        function validerPret(id) {
            if (id) {
                if (confirm("Êtes-vous sûr de vouloir valider ce prêt ?")) {
                    ajax("GET", `/prets/validation/${id}`, null, (response, status) => {
                        if (status === 200 && response.success) {
                            alert(response.message);
                            chargerPret(); // recharge la liste
                        } else {
                            alert("Erreur : " + (response.message || "Échec de validation."));
                        }
                    });
                }
            }
        }

        function refuserPret(id) {
            if (id) {
                if (confirm("Êtes-vous sûr de vouloir rejeter ce prêt ?")) {
                    ajax("GET", `/prets/rejet/${id}`, null, (response, status) => {
                        if (status === 200 && response.success) {
                            alert(response.message);
                            chargerPret(); // recharge la liste
                        } else {
                            alert("Erreur : " + (response.message || "Échec du rejet."));
                        }
                    });
                }
            }
        }

        function afficherPdfPret(id) {
            if (id) {
                // Ouvrir la page PDF dans un nouvel onglet
                const url = `${apiBase}/prets/pdf/${id}`;
                window.open(url, '_blank');
            }
        }

        function getEtatClass(etat) {
            const etatLower = etat.toLowerCase();
            if (etatLower.includes('attente')) return 'etat-attente';
            if (etatLower.includes('approuvé') || etatLower.includes('approuve')) return 'etat-approuve';
            if (etatLower.includes('rejeté') || etatLower.includes('rejete')) return 'etat-rejete';
            if (etatLower.includes('clôturé') || etatLower.includes('cloture')) return 'etat-cloture';
            return '';
        }

        function getActionsButtons(etat, id) {
            const etatLower = etat.toLowerCase();
            
            if (etatLower.includes('approuvé') || etatLower.includes('approuve')) {
                // Si approuvé, seulement le bouton PDF
                return `
                    <button class="btn-pdf" onclick="afficherPdfPret(${id})">
                        Afficher PDF
                    </button>
                `;
            }
            
            if (etatLower.includes('rejeté') || etatLower.includes('rejete')) {
                // Si rejeté, seulement le bouton PDF
                return `
                    <button class="btn-pdf" onclick="afficherPdfPret(${id})">
                        Afficher PDF
                    </button>
                `;
            }
            
            if (etatLower.includes('attente')) {
                // Si en attente, afficher les trois boutons + PDF
                return `
                    <button class="btn-simuler" onclick="simulerPret(${id})">
                        Simuler
                    </button>
                    <button class="btn-valider" onclick="validerPret(${id})">
                        Valider
                    </button>
                    <button class="btn-refuser" onclick="refuserPret(${id})">
                        Refuser
                    </button>
                    <button class="btn-pdf" onclick="afficherPdfPret(${id})">
                        Afficher PDF
                    </button>
                `;
            }
            
            if (etatLower.includes('simulé') || etatLower.includes('simule')) {
                // Si simulé, afficher valider, refuser et PDF
                return `
                    <button class="btn-valider" onclick="validerPret(${id})">
                        Valider
                    </button>
                    <button class="btn-refuser" onclick="refuserPret(${id})">
                        Refuser
                    </button>
                    <button class="btn-pdf" onclick="afficherPdfPret(${id})">
                        Afficher PDF
                    </button>
                `;
            }
            
            // Par défaut, seulement le bouton PDF
            return `
                <button class="btn-pdf" onclick="afficherPdfPret(${id})">
                    Afficher PDF
                </button>
            `;
        }

        function chargerPret() {
            ajax("GET", "/prets/liste", null, (data) => {
                toutesLesPrets = data;
                filtrerEtAfficher();
            });
        }

        function filtrerEtAfficher() {
            const dateDebutStr = document.getElementById("date-debut").value;
            const dateFinStr = document.getElementById("date-fin").value;
            const etatChoisi = document.getElementById("etat-filtre").value;

            const dateDebut = dateDebutStr ? new Date(dateDebutStr) : null;
            const dateFin = dateFinStr ? new Date(dateFinStr) : null;

            const tbody = document.querySelector("#listePret tbody");
            tbody.innerHTML = "";

            toutesLesPrets.forEach(e => {
                const datePret = new Date(e.date_pret);

                const correspondDate =
                    (!dateDebut || datePret >= dateDebut) &&
                    (!dateFin || datePret <= dateFin);

                const correspondEtat =
                    etatChoisi === "" || e.etat_pret === etatChoisi;

                if (correspondDate && correspondEtat) {
                    const tr = document.createElement("tr");
                    const etatClass = getEtatClass(e.etat_pret);
                    const actionsHtml = getActionsButtons(e.etat_pret, e.id_pret);

                    tr.innerHTML = `
                        <td>${e.id_pret}</td>
                        <td><span class="${etatClass}">${e.etat_pret}</span></td>
                        <td>${e.numero_compte}</td>
                        <td>${e.client}</td>
                        <td>${parseFloat(e.montant).toLocaleString('fr-FR')} Ar</td>
                        <td>${new Date(e.date_pret).toLocaleDateString('fr-FR')}</td>
                        <td>${e.duree_remboursement}</td>
                        <td class="actions-cell">
                            ${actionsHtml}
                        </td>
                        <td class="checkbox-cell">
                            <input type="checkbox" name="pret_checkbox" value="${e.id_pret}">
                        </td>
                    `;
                    tbody.appendChild(tr);
                }
            });
        }

        document.getElementById("date-debut").addEventListener("input", filtrerEtAfficher);
        document.getElementById("date-fin").addEventListener("input", filtrerEtAfficher);
        document.getElementById("etat-filtre").addEventListener("change", filtrerEtAfficher);

        chargerPret();
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>