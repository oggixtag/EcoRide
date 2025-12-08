<?php
echo '<pre>';
var_dump('page: ecoride\app\Views\covoiturage\journey.php');
echo '</pre>';
?>

<?php if (isset($error_form_empty)): ?>
    <?php if ($error_form_empty): ?>
        <div class="alert alert-danger">
            Le formulaire est vide
        </div>

    <?php else: ?>

        <div class="ecoride-trip-container">

            <h2 class="section-title">Section voyages</h2>

            <h3 class="section-soustitle">Prochains</h3>
            <?php if (empty($covoiturage)) { ?>
                <div class="trip-row-empty">Aucun voyage prévu pour le moment.</div>
            <?php } else { ?>

                <!-- L'entête du "tableau" (caché sur mobile via CSS) -->
                <div class="trip-row trip-header">
                    <div class="trip-cell">Date</div>
                    <div class="trip-cell">Heure</div>
                    <div class="trip-cell">Départ</div>
                    <div class="trip-cell">Statut</div>
                    <div class="trip-cell">Détails</div>
                </div>

                <div class="trip-row">
                    <!-- Chaque cellule a un attribut data-label pour le responsive mobile -->
                    <div class="trip-cell" data-label="Date">
                        <?= htmlspecialchars($covoiturage->date_depart); ?>
                    </div>

                    <div class="trip-cell" data-label="Heure">
                        <?= htmlspecialchars($covoiturage->heure_depart); ?>
                    </div>

                    <div class="trip-cell" data-label="Départ">
                        <strong><?= htmlspecialchars($covoiturage->lieu_depart); ?></strong>
                    </div>

                    <div class="trip-cell" data-label="Statut">
                        <span class="status-badge <?= strtolower($covoiturage->statut); ?>">
                            <?= htmlspecialchars($covoiturage->statut); ?>
                        </span>
                    </div>

                    <div class="trip-cell" data-label="Détails">
                        <?= $covoiturage->getExtrait(); ?>
                    </div>
                </div>
            <?php

            } ?>

        </div>
    <?php endif; ?>
<?php endif; ?>

<a href="index.php">Retour à la recherche</a>