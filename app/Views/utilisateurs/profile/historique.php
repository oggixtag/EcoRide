<?php if (isset($utilisateur)): ?>
    <section class="presentation-section">
        <div class="presentation-content">
            <div class="dashboard-header" style="background: none; color: inherit; padding: 0; box-shadow: none; display:flex; justify-content:space-between; align-items:center;">
                <h1>Historique des Covoiturages</h1>
                <a href="index.php?p=utilisateurs.profile.index" class="btn btn-secondary">Retour au profil</a>
            </div>
            <p>Historique pour <?php echo htmlspecialchars($utilisateur->pseudo ?? ''); ?></p>
        </div>
    </section>

    <!-- Historique Chauffeur -->
    <?php if (!empty($historique_chauffeur)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Covoiturages réalisés (Chauffeur)</h2>
                <div class="covoiturages-list" style="text-align: left;">
                    <?php foreach ($historique_chauffeur as $covoiturage): ?>
                        <div class="covoiturage-item" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; opacity: 0.8; background-color: #f9f9f9;">
                            <h3><?php echo htmlspecialchars($covoiturage->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($covoiturage->lieu_arrivee ?? ''); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($covoiturage->date_depart ?? ''); ?></p>
                            <p><strong>Statut:</strong> <?php echo htmlspecialchars($covoiturage->statut ?? ''); ?></p>
                            <p><strong>Prix:</strong> <?php echo htmlspecialchars($covoiturage->prix_personne ?? ''); ?> €</p>
                            <p><strong>Places:</strong> <?php echo htmlspecialchars($covoiturage->nb_place ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Historique Passager -->
    <?php if (!empty($historique_passager)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Covoiturages passés (Passager)</h2>
                <div class="reservations-list" style="text-align: left;">
                    <?php foreach ($historique_passager as $reservation): ?>
                        <div class="reservation-item" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; opacity: 0.8; background-color: #f9f9f9;">
                            <h3><?php echo htmlspecialchars($reservation->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($reservation->lieu_arrivee ?? ''); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($reservation->date_depart ?? ''); ?></p>
                            <p><strong>Chauffeur:</strong> <?php echo htmlspecialchars($reservation->pseudo ?? ''); ?></p>
                            <p><strong>Statut:</strong> <?php echo htmlspecialchars($reservation->statut ?? ''); ?></p>
                            <p><strong>Prix:</strong> <?php echo htmlspecialchars($reservation->prix_personne ?? ''); ?> €</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (empty($historique_chauffeur) && empty($historique_passager)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <p class="empty-message">Aucun historique disponible.</p>
            </div>
        </section>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-danger">Utilisateur non trouvé.</div>
<?php endif; ?>
