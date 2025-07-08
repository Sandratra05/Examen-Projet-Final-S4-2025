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
    let toutesLesPrets = [];

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
                    response = { message: "Réponse invalide du serveur." };
                }
                callback(response, xhr.status);
            }
        };
        xhr.send(data);
    }

    function simulerPret(id) {
        if (id) {
            ajax("GET", `/prets/simulation/${id}`, null, (response, status) => {
                if (status === 200 && response.success) {
                    alert(response.message);
                    chargerPret();
                } else {
                    alert("Erreur : " + (response.message || "Échec de simulation."));
                }
            });
        }
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
