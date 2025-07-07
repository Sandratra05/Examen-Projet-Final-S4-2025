const apiBase = "http://localhost:80/ETU003197/t/Examen-Projet-Finale-S4-2025/ws";

function ajax(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, apiBase + url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
        }
    };
    xhr.send(data);
}