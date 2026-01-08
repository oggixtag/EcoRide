// Script pour mettre à jour les valeurs affichées des sliders de filtres
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
      input.addEventListener("input", update);
      update();
    });

    // Gestion du bouton Réinitialiser
    var resetButton = document.querySelector(".btn-reset");
    if (resetButton) {
      resetButton.addEventListener("click", function (e) {
        e.preventDefault(); // Empêcher le comportement par défaut du reset

        // Réinitialiser les champs du formulaire à leurs valeurs par défaut
        var filterForm = document.getElementById("form-filters");
        if (filterForm) {
          // Réinitialiser les sliders à leurs valeurs par défaut
          document.getElementById("prix_min").value = "0";
          document.getElementById("prix_max").value = "50";
          document.getElementById("duree_max").value = "20";
          document.getElementById("score_min").value = "4";

          // Décocher les checkboxes
          var checkboxes = filterForm.querySelectorAll(
            'input[type="checkbox"]'
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });

          // Mettre à jour les labels des sliders
          sliders.forEach(function (s) {
            var label = document.getElementById(s.label);
            if (label) {
              var input = document.getElementById(s.id);
              label.textContent = input.value;
            }
          });

          // Soumettre le formulaire pour recharger les résultats sans filtres
          filterForm.submit();
        }
      });
    }
  });
})();
