<section class="presentation-section">
    <div class="presentation-content">
        <h1>Vue d'ensemble des covoiturages présents à ce jour sur la plateforme</h1>

        <div class="trip-row trip-header">
            <div class="trip-cell">Date</div>
            <div class="trip-cell">Départ</div>
            <div class="trip-cell">Heure</div>
            <div class="trip-cell">Destination</div>
            <div class="trip-cell">Arrivée</div>
            <div class="trip-cell">Statut</div>
            <div class="trip-cell">Détails</div>
        </div>

        <?php foreach ($covoiturages as $covoiturage): ?>

            <div class="trip-row">
                <!-- Chaque cellule a un attribut data-label pour le responsive mobile -->
                <div class="trip-cell" data-label="Date">
                    <?= htmlspecialchars(date('d-m-Y', strtotime($covoiturage->date_depart))); ?>
                </div>

                <div class="trip-cell" data-label="Départ">
                    <strong><?= htmlspecialchars($covoiturage->lieu_depart); ?></strong>
                </div>

                <div class="trip-cell" data-label="Heure">
                    <?= htmlspecialchars(substr($covoiturage->heure_depart, 0, 5)); ?>
                </div>

                <div class="trip-cell" data-label="Destination">
                    <strong><?= htmlspecialchars($covoiturage->lieu_arrivee); ?></strong>
                </div>

                <div class="trip-cell" data-label="Arrivée">
                    <?= htmlspecialchars(substr($covoiturage->heure_arrivee, 0, 5)); ?>
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
        <?php endforeach; ?>
    </div>
</section>