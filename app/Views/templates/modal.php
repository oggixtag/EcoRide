<?php
echo '<pre>';
var_dump('page modal: ecoride\app\Views\templates\modal.php');
echo '</pre>';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Covoiturage - EcoRide</title>
    <!-- CSS général de l'application -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_app.css">
    <!-- CSS spécifique aux détails de trajet (modal) -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_trajet_details.css">
</head>

<body>
    <!-- Le contenu du modal sera inséré ici -->
    <?= $content; ?>

    <!-- Script pour le modal (inclus dans chaque réponse AJAX) -->
    <script>
        // Recalculer la durée du voyage quand le contenu est chargé
        (function() {
            const durationEl = document.getElementById('journey-duration');
            if (durationEl) {
                // Les dates sont déjà dans le HTML, extrayons-les
                const journeyPoints = document.querySelectorAll('.journey-point');
                if (journeyPoints.length >= 2) {
                    // Récupérer les données du premier et dernier journey-point
                    const timeText = journeyPoints[0].querySelector('.time').textContent;
                    const dateText = journeyPoints[0].querySelector('.date').textContent;

                    const arrivalTimeText = journeyPoints[1].querySelector('.time').textContent;
                    const arrivalDateText = journeyPoints[1].querySelector('.date').textContent;

                    const departStr = dateText + ' ' + timeText;
                    const arriveeStr = arrivalDateText + ' ' + arrivalTimeText;

                    const depart = new Date(departStr);
                    const arrivee = new Date(arriveeStr);

                    if (!isNaN(depart) && !isNaN(arrivee)) {
                        const diffMs = arrivee - depart;
                        const diffMins = Math.floor(diffMs / 60000);
                        const heures = Math.floor(diffMins / 60);
                        const minutes = diffMins % 60;

                        let dureeTexte = '';
                        if (heures > 0) {
                            dureeTexte += heures + 'h';
                        }
                        if (minutes > 0) {
                            dureeTexte += ' ' + minutes + 'min';
                        }

                        durationEl.textContent = dureeTexte || '0min';
                    }
                }
            }
        })();
    </script>
</body>

</html>