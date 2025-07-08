<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Prêts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e3f2fd;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 2px;
        }

        .btn-simuler {
            background-color: #2ecc71;
            color: white;
        }

        .btn-refuser {
            background-color: #e74c3c;
            color: white;
        }

        .btn-simuler:hover {
            background-color: #27ae60;
        }

        .btn-refuser:hover {
            background-color: #c0392b;
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
    </style>
</head>

<body>
    <h1>Liste des Demandes de Prêt</h1>

    <table id="listePret">
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
</body>

</html>