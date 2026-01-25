<?php if (isset($utilisateur)): ?>
    <section class="presentation-section">
        <div class="presentation-content">
            <div class="dashboard-header header-grid header-plain">
                <div></div>
                <h1 class="header-grid-title">Historique des Covoiturages</h1>
                <div class="text-right">
                    <a href="index.php?p=utilisateurs.profile.index" class="btn btn-secondary">Retour au profil</a>
                </div>
            </div>
            <p class="subtitle-centered">Historique pour <?php echo htmlspecialchars($utilisateur->pseudo ?? ''); ?></p>
        </div>
    </section>

    <!-- Historique Chauffeur -->
    <?php if (!empty($historique_chauffeur)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Covoiturages réalisés (Chauffeur)</h2>
                <div class="covoiturages-list text-left">
                    <?php foreach ($historique_chauffeur as $covoiturage): ?>
                        <div class="covoiturage-item history-item">
                            <h3><?php echo htmlspecialchars($covoiturage->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($covoiturage->lieu_arrivee ?? ''); ?></h3>
                            <p>
                                <strong>Date:</strong> <?php echo date('d/m/Y', strtotime($covoiturage->date_depart)); ?> à <?php echo substr($covoiturage->heure_depart, 0, 5); ?>
                            </p>
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
                <div class="reservations-list text-left">
                    <?php foreach ($historique_passager as $reservation): ?>
                        <div class="reservation-item history-item">
                            <h3><?php echo htmlspecialchars($reservation->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($reservation->lieu_arrivee ?? ''); ?></h3>
                            <p>
                                <strong>Date:</strong> <?php echo date('d/m/Y', strtotime($reservation->date_depart)); ?> à <?php echo substr($reservation->heure_depart, 0, 5); ?>
                            </p>
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
