$(document).ready(function () {
    var adresseInput = $('#ajout_client_form_adresse');
    var resultatDiv = $('#resultat');
  
    adresseInput.on('keyup', function (e) {
      e.preventDefault();
      var inputValue = adresseInput.val();
  
      if (inputValue.length >= 3) {
        $.ajax({
          url: "https://api-adresse.data.gouv.fr/search/",
          type: 'GET',
          data: {
            q: inputValue
          },
          success: function (response) {
            var labels = response.features.map(function (result) {
              return result.properties.label;
            });
  
            resultatDiv.empty();
  
            if (labels.length > 0) {

              $.each(labels, function (_, label) {
                resultatDiv.append("<div class='adresse-item'>" + label + "</div>");
                console.log(labels);
              });
  
              resultatDiv.on('click', '.adresse-item', function () {
                var selectedAdresse = $(this).text();
  
                // Recherchez la correspondance dans la réponse de l'API
                var selectedResult = response.features.find(function (result) {
                  return result.properties.label === selectedAdresse;
                });
  
                if (selectedResult) {
                  // Assurez-vous d'avoir des identifiants (IDs) correspondant à vos champs dans le formulaire HTML
                  var codePostalInput = $("#ajout_client_form_code_postal");
                  var numeroVoieInput = $("#ajout_client_form_numero_voie");
                  var villeInput = $("#ajout_client_form_ville");
            

                  // Mettez à jour les champs du formulaire avec les informations de l'adresse
                  codePostalInput.val(selectedResult.properties.postcode || '');
                  numeroVoieInput.val(selectedResult.properties.housenumber || '');
                  villeInput.val(selectedResult.properties.city || '');
                  adresseInput.val(selectedResult.properties.label || ''); // Mise à jour du champ adresse
                  resultatDiv.empty();
                }
              });
            } else {
              resultatDiv.append("<div>Aucun résultat trouvé.</div>");
            }
          },
          error: function (response) {
            console.error('Erreur lors de la requête Ajax :', response);
          }
        });
      }
    });
  });
