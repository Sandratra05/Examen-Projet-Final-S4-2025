<?php include 'header.php'; ?>

<body>
    <main class="form-main">
        <section>
            <h2 class="form-title">Liste des Demandes de Prêt</h2>
            <div style="margin-bottom:20px;">
                <label for="date-search"><b>Date de référence :</b></label>
                <input
                    type="datetime-local"
                    id="date-search"
                    value="<?= date('Y-m-d\TH:i') ?>"
                    style="margin-left:10px;" />
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
                        <th>Date de pret</th>
                        <th>Duree remboursement(mois)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
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
                ajax("GET", `/prets/simulation/${id}`, null, (response, status) => {
                    if (status === 200 && response.success) {
                        alert(response.message); // ou afficher dans une div
                        chargerPret(); // recharge la liste
                    } else {
                        alert("Erreur : " + (response.message || "Échec de simulation."));
                    }
                });
            }
        }

        function chargerPret() {
            ajax("GET", "/prets/liste", null, (data) => {
                const tbody = document.querySelector("#listePret tbody");
                tbody.innerHTML = "";
                data.forEach(e => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                <td>${e.id_pret}</td>
                <td>${e.etat_pret}</td>
                <td>${e.numero_compte}</td>
                <td>${e.client}</td>
                <td>${e.montant}</td>
                <td>${e.date_pret}</td>
                <td>${e.duree_remboursement}</td>
                <td>
                    <button class="btn btn-simuler" onclick="simulerPret(${e.id_pret})">Simuler</button>
                    <button class="btn btn-refuser" onclick="refuserPret(${e.id_pret})">Refuser</button>
                </td>
            `;
                    tbody.appendChild(tr);
                });
            });
        }


        chargerPret();
    </script>
<?php include 'footer.php'; ?>