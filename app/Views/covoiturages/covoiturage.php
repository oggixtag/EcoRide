<?php
echo '<pre>';
var_dump('page: ecoride\app\Views\covoiturages\covoiturage.php');
echo '</pre>';
?>

<div class="trip-row trip-header">
    <div class="trip-cell">Date</div>
    <div class="trip-cell">Heure</div>
    <div class="trip-cell">Départ</div>
    <div class="trip-cell">Statut</div>
    <div class="trip-cell">Détails</div>
</div>

<?php foreach ($covoiturages as $covoiturage): ?>

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
endforeach;
?>