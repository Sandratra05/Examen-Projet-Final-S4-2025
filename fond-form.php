<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Ajout de fond</h1>
    <!-- <label for="nom">Nom de l'&eacute;tablissement</label>
    <input type="text" name="nom" id="nom"> -->
    <input type="hidden" name="id" id="id" value="1">
    <label for="solde">Fond</label>
    <input type="number" name="solde" id="solde" required>
    <button type="submit"  onclick="insererFond()">Valider</button>

    <script src="ajax.js"></script>
    <script>
        
        function insererFond() {
            const solde = document.getElementById("solde").value;
            const id = document.getElementById("id").value;
            const data = `solde=${encodeURIComponent(solde)}&id=${encodeURIComponent(id)}`;

            if (solde > 0) {
                ajax("POST", "/fonds", data, () => {
                    console.log("Insertion reussi");
                });
            }
        }
    </script>
</body>
</html>