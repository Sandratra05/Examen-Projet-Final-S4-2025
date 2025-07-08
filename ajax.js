const apiBase = "http://localhost:80/ETU003197/t/Examen-Projet-Finale-S4-2025/ws";

// function ajax(method, url, data, callback) {
//     const xhr = new XMLHttpRequest();
//     xhr.open(method, apiBase + url, true);
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.onreadystatechange = () => {
//         if (xhr.readyState === 4) {
//             console.log("Réponse brute du serveur :", xhr.responseText); // debug ici
//             if (xhr.status === 200) {
//                 try {
//                     // const json = JSON.parse(xhr.responseText);
//                     callback(xhr.responseText);
//                 } catch (err) {
//                     console.error("Erreur de parsing JSON :", err);
//                 }
//             } else {
//                 console.error("Erreur HTTP", xhr.status);
//             }
//         }
//     };
//     xhr.send(data);
// }

function ajax(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, apiBase + url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            console.log("Status:", xhr.status);
            console.log("Réponse brute du serveur :", xhr.responseText);
            
            if (xhr.status === 200) {
                try {
                    // CORRECTION: Parser le JSON et le passer au callback
                    const json = JSON.parse(xhr.responseText);
                    callback(json);
                } catch (err) {
                    console.error("Erreur de parsing JSON :", err);
                    console.error("Réponse reçue :", xhr.responseText);
                    // Callback avec une structure d'erreur
                    callback({
                        success: false,
                        errors: ['Erreur de format de réponse du serveur'],
                        data: []
                    });
                }
            } else {
                console.error("Erreur HTTP", xhr.status);
                console.error("Réponse d'erreur:", xhr.responseText);
                // Callback avec une structure d'erreur
                callback({
                    success: false,
                    errors: ['Erreur serveur HTTP ' + xhr.status],
                    data: []
                });
            }
        }
    };
    
    xhr.send(data);
}