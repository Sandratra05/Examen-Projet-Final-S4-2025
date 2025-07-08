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
        }

        .btn-refuser {
            background-color: #e74c3c;
            color: white;
        }

        .btn-pdf {
            background-color: #9b59b6;
            color: white;
        }

        .btn-simuler:hover {
            background-color: #27ae60;
        }

        .btn-refuser:hover {
            background-color: #c0392b;
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
                    <option value="Validé">Validé</option>
                    <option value="Refusé">Refusé</option>
                    <!-- Ajoute d'autres états si nécessaire -->
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
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
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

        function refuserPret(id) {
            if (id) {
                if (confirm("Êtes-vous sûr de vouloir refuser ce prêt ?")) {
                    // Logique pour refuser le prêt
                    alert("Fonctionnalité à implémenter");
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

        function chargerPret() {
            ajax("GET", "/prets/liste", null, (data) => {
                toutesLesPrets = data;
                filtrerEtAfficher();
                const tbody = document.querySelector("#listePret tbody");
                tbody.innerHTML = "";

                data.forEach(e => {
                    const tr = document.createElement("tr");
                    const etatClass = getEtatClass(e.etat_pret);
                    const estSimule = e.etat_pret.toLowerCase().includes('clôturé') || e.etat_pret.toLowerCase().includes('cloture');

                    let actionsHtml = '';

                    if (estSimule) {
                        // Si déjà simulé, afficher seulement le bouton PDF
                        actionsHtml = `
                            <button class="btn btn-pdf" onclick="afficherPdfPret(${e.id_pret})">
                                Afficher PDF
                            </button>
                        `;
                    } else {
                        // Si pas encore simulé, afficher les boutons simuler et refuser
                        actionsHtml = `
                            <button class="btn btn-simuler" onclick="simulerPret(${e.id_pret})">
                                Simuler
                            </button>
                            <button class="btn btn-refuser" onclick="refuserPret(${e.id_pret})">
                                Refuser
                            </button>
                        `;
                    }

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
                    `;
                    tbody.appendChild(tr);
                });
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
                    tr.innerHTML = `
                    <td>${e.id_pret}</td>
                    <td>${e.etat_pret}</td>
                    <td style="text-align:right;">${e.numero_compte}</td>
                    <td>${e.client}</td>
                    <td>${e.montant}</td>
                    <td>${e.date_pret}</td>
                    <td style="text-align:right;">${e.duree_remboursement}</td>
                    <td>
                        <button class="btn btn-simuler action" onclick="simulerPret(${e.id_pret})">Simuler</button>
                        <button class="btn btn-refuser action" onclick="refuserPret(${e.id_pret})">Refuser</button>
                    </td>`;
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