// Script pour mettre à jour les valeurs affichées des sliders de filtres et gérer l'AJAX
(function () {
  var sliders = [
    {
      id: "prix_min",
      label: "label-prix-min",
      suffix: "€",
    },
    {
      id: "prix_max",
      label: "label-prix-max",
      suffix: "€",
    },
    {
      id: "duree_max",
      label: "label-duree",
      suffix: "h",
    },
    {
      id: "score_min",
      label: "label-score",
      suffix: "★",
    },
  ];

  document.addEventListener("DOMContentLoaded", function () {
    // Initialiser les labels des sliders
    sliders.forEach(function (s) {
      var input = document.getElementById(s.id);
      var label = document.getElementById(s.label);
      if (!input || !label) return;
      var update = function () {
        label.textContent = input.value;
      };
      input.addEventListener("input", update); // Mettre à jour le label en temps réel
      update();
    });

    // --- LOGIQUE AJAX ---
    var filterForm = document.getElementById("form-filters");
    if (filterForm) {
      var resultsContainer = document.getElementById("liste-resultats");
      var submitButton = filterForm.querySelector('button[type="submit"]');

      // Cacher le bouton "Appliquer" car on met à jour en temps réel
      if (submitButton) {
        submitButton.style.display = "none";
      }

      // Fonction Debounce pour limiter les appels AJAX
      function debounce(func, wait) {
        var timeout;
        return function () {
          var context = this;
          var args = arguments;
          clearTimeout(timeout);
          timeout = setTimeout(function () {
            func.apply(context, args);
          }, wait);
        };
      }

      // Fonction pour envoyer la requête AJAX
      var fetchResults = function () {
        var formData = new FormData(filterForm);
        formData.append("ajax", "1"); // Indiquer au contrôleur que c'est une requête AJAX

        // Ajouter une classe de chargement optionnelle
        if (resultsContainer) {
          resultsContainer.style.opacity = "0.5";
        }

        fetch(filterForm.action, {
          method: "POST",
          body: formData,
        })
          .then(function (response) {
            if (!response.ok) {
              throw new Error("Erreur réseau");
            }
            return response.text();
          })
          .then(function (html) {
            if (resultsContainer) {
              resultsContainer.innerHTML = html;
              resultsContainer.style.opacity = "1";
            }
          })
          .catch(function (error) {
            console.error("Erreur:", error);
            if (resultsContainer) {
              resultsContainer.style.opacity = "1";
            }
          });
      };

      // Version debouncée pour les inputs (sliders)
      var debouncedFetch = debounce(fetchResults, 300);

      // Ajouter les écouteurs d'événements
      var inputs = filterForm.querySelectorAll("input, select");
      inputs.forEach(function (input) {
        if (input.type === "range" || input.type === "text") {
          input.addEventListener("input", debouncedFetch);
        } else if (input.type === "checkbox" || input.type === "radio" || input.tagName === "SELECT") {
          input.addEventListener("change", fetchResults); // Pas besoin de debounce pour checkbox/select
        }
      });

      // Gestion du bouton Réinitialiser
      var resetButton = document.querySelector(".btn-reset");
      if (resetButton) {
        resetButton.addEventListener("click", function (e) {
          e.preventDefault();

          // Réinitialiser les valeurs (code existant + trigger AJAX)
          document.getElementById("prix_min").value = "0";
          document.getElementById("prix_max").value = "50";
          document.getElementById("duree_max").value = "20";
          document.getElementById("score_min").value = "4";

          var checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });

          // Mettre à jour les labels
          sliders.forEach(function (s) {
            var label = document.getElementById(s.label);
            if (label) {
              var input = document.getElementById(s.id);
              label.textContent = input.value;
            }
          });

          // Lancer la recherche AJAX avec les valeurs réinitialisées
          fetchResults();
        });
      }
    }
  });
})();
