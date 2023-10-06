

var adresseInput = document.getElementById('adresse');

adresseInput.addEventListener('keyup', function () {

    var adresseVal = adresseInput.value;

    var apiUrl = 'https://api-adresse.data.gouv.fr/search/?q=' + encodeURIComponent(adresseVal);

    fetch(apiUrl)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('La requête n\'a pas abouti : ' + response.status);
            }
            return response.json(); // Convertit la réponse JSON en objet JavaScript
        })
        .then(function (data) {
            // Gère les données de réponse (suggestions d'adresse) ici
            console.log(data);
        })
        .catch(function (error) {
            // Gère les erreurs ici
            console.error(error);
        });
        
})