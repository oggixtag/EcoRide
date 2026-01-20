<section class="presentation-section">
    <div class="presentation-content">
        <div class="dashboard-header">
            <h1>Mon Tableau de Bord</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($utilisateur->pseudo ?? ''); ?> !</p>
        </div>

        <!-- Section Informations Personnelles -->
        <section class="dashboard-section">
            <h2>Mes Informations Personnelles</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nom</label>
                    <p><?php echo htmlspecialchars($utilisateur->nom ?? ''); ?></p>
                </div>
                <div class="info-item">
                    <label>Prénom</label>
                    <p><?php echo htmlspecialchars($utilisateur->prenom ?? ''); ?></p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p><?php echo htmlspecialchars($utilisateur->email ?? ''); ?></p>
                </div>
                <div class="info-item">
                    <label>Téléphone</label>
                    <p><?php echo htmlspecialchars($utilisateur->telephone ?? ''); ?></p>
                </div>
                <div class="info-item">
                    <label>Adresse</label>
                    <p><?php echo htmlspecialchars($utilisateur->adresse ?? ''); ?></p>
                </div>
                <div class="info-item">
                    <label>Date de Naissance</label>
                    <p><?php echo htmlspecialchars($utilisateur->date_naissance ?? ''); ?></p>
                </div>
                <div class="info-item">
                    <label>Crédits</label>
                    <p class="credit-amount"><?php echo htmlspecialchars($utilisateur->credit ?? 0); ?> €</p>
                </div>
            </div>
        </section>

        <!-- Section Rôle 
        <php if ($role): >
            <section class="dashboard-section">
                <h2>Mon Rôle</h2>
                <div class="role-info">
                    <p class="role-badge"><php echo htmlspecialchars($role ?? 'Non défini'); ?></p>
                </div>
            </section>
        <php endif; >-->

        <!-- Section Voitures -->
        <?php if (!empty($voitures)): ?>
            <section class="dashboard-section">
                <h2>Mes Voitures</h2>
                <div class="voitures-list">
                    <?php foreach ($voitures as $voiture): ?>
                        <div class="voiture-item">
                            <h3><?php echo htmlspecialchars($voiture->marque ?? ''); ?> - <?php echo htmlspecialchars($voiture->modele ?? ''); ?></h3>
                            <p><strong>Immatriculation:</strong> <?php echo htmlspecialchars($voiture->immatriculation ?? ''); ?></p>
                            <p><strong>Energie:</strong> <?php echo htmlspecialchars($voiture->energie ?? ''); ?></p>
                            <p><strong>Couleur:</strong> <?php echo htmlspecialchars($voiture->couleur ?? ''); ?></p>
                            <p><strong>Année:</strong> <?php echo htmlspecialchars($voiture->annee ?? ''); ?></p>
                            <p><strong>Places:</strong> <?php echo htmlspecialchars($voiture->nb_places ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section class="dashboard-section">
                <h2>Mes Voitures</h2>
                <p class="empty-message">Aucune voiture enregistrée.</p>
            </section>
        <?php endif; ?>
        
        <!-- Section Avis -->
        <?php if (!empty($avis)): ?>
            <section class="dashboard-section">
                <h2>Mes Avis</h2>
                <div class="avis-list">
                    <?php foreach ($avis as $av): ?>
                        <div class="avis-item">
                            <div class="avis-header">
                                <span class="avis-note"><?php echo htmlspecialchars($av->note ?? ''); ?>/5</span>
                            </div>
                            <p class="avis-text"><?php echo htmlspecialchars($av->commentaire ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section class="dashboard-section">
                <h2>Mes Avis</h2>
                <p class="empty-message">Aucun avis pour le moment.</p>
            </section>
        <?php endif; ?>

        <!-- Section Covoiturages -->
        <?php if (!empty($covoiturages)): ?>
            <section class="dashboard-section">
                <h2>Mes Trajets en Covoiturage (en tant que conducteur)</h2>
                <div class="covoiturages-list">
                    <?php foreach ($covoiturages as $covoiturage): ?>
                        <div class="covoiturage-item">
                            <h3><?php echo htmlspecialchars($covoiturage->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($covoiturage->lieu_arrivee ?? ''); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($covoiturage->date_depart ?? ''); ?></p>
                            <p><strong>Heure:</strong> <?php echo htmlspecialchars(substr($covoiturage->heure_depart ?? '', 0, 5)); ?></p>
                            <p><strong>Places disponibles:</strong> <?php echo htmlspecialchars($covoiturage->nb_place ?? ''); ?></p>
                            <p><strong>Prix:</strong> <?php echo htmlspecialchars($covoiturage->prix_personne ?? ''); ?> €</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section class="dashboard-section">
                <h2>Mes Trajets en Covoiturage (en tant que conducteur)</h2>
                <p class="empty-message">Aucun trajet créé pour le moment.</p>
            </section>
        <?php endif; ?>

        <!-- Section Réservations -->
        <?php if (!empty($reservations)): ?>
            <section class="dashboard-section">
                <h2>Mes Réservations (en tant que passager)</h2>
                <div class="reservations-list">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-item">
                            <h3><?php echo htmlspecialchars($reservation->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($reservation->lieu_arrivee ?? ''); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($reservation->date_depart ?? ''); ?></p>
                            <p><strong>Heure:</strong> <?php echo htmlspecialchars($reservation->heure_depart ?? ''); ?></p>
                            <p><strong>Conducteur:</strong> <?php echo htmlspecialchars($reservation->pseudo ?? ''); ?></p>
                            <p><strong>Prix:</strong> <?php echo htmlspecialchars($reservation->prix_personne ?? ''); ?> €</p>
                            <p><strong>Statut:</strong> <?php echo htmlspecialchars($reservation->statut ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section class="dashboard-section">
                <h2>Mes Réservations (en tant que passager)</h2>
                <p class="empty-message">Aucune réservation pour le moment.</p>
            </section>
        <?php endif; ?>
    </div>
</section>