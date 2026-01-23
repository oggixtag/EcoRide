<?php if (isset($auth_type) && $auth_type === 'visiteur'): ?>
    
    <section class="presentation-section">
        <div class="presentation-content">
            <h1>Finalisation de l'inscription</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($visiteur->pseudo ?? ''); ?> !</p>

            <?php if (isset($statut_mail_id) && $statut_mail_id == 1): ?>
                <!-- STATUT 1: Mail à confirmer -->
                <div class="alert alert-warning" style="margin-top: 20px;">
                    <p><strong>Mail à confirmer</strong></p>
                    <p>Veuillez cliquer sur le bouton ci-dessous pour valider votre adresse email.</p>
                    <br>
                    <a href="index.php?p=utilisateurs.validerEmail" class="btn btn-primary">Valider votre adresse mail</a>
                </div>
            
            <?php elseif (isset($statut_mail_id) && $statut_mail_id == 2): ?>
                <!-- STATUT 2: Formulaire complet -->
                <h2 style="margin-top: 20px;">Mes Informations Personnelles</h2>
                <p>Votre email est confirmé. Veuillez compléter votre profil pour accéder à toutes les fonctionnalités.</p>
                
                <form action="index.php?p=utilisateurs.finaliserInscription" method="POST" class="form-utilisateurs" style="text-align: left; max-width: 600px; margin: 0 auto;">
                    <div class="form-group-row">
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($visiteur->email); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date de Naissance *</label>
                        <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Enregistrement</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>

<?php else: ?>
    <!-- VIEW UTILISATEUR COMPLET -->
    
    <section class="presentation-section">
        <div class="presentation-content">
            <h1>Mon Tableau de Bord</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($utilisateur->pseudo ?? ''); ?> !</p>
        </div>
    </section>

    <!-- Section Informations Personnelles -->
    <section class="presentation-section">
        <div class="presentation-content">
            <h2>Mes Informations Personnelles <a href="index.php?p=utilisateurs.profile.edit" class="btn btn-sm btn-secondary float-right-btn">Modifier</a></h2>
            <div class="info-grid" style="text-align: left;">
                <div class="info-item">
                    <label>Mon Rôle</label>
                    <p class="role-badge">
                        <?php 
                        if ($utilisateur->role_id == 1) {
                            echo 'Chauffeur';
                        } elseif ($utilisateur->role_id == 3) {
                            echo 'Chauffeur-Passager';
                        } else {
                            echo 'Passager';
                        }
                        ?>
                    </p>
                </div>
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
        </div>
    </section>
    
    <!-- Section Voitures -->
    <?php if (!empty($voitures)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Voitures <a href="index.php?p=utilisateurs.voitures.index" class="btn btn-sm btn-secondary float-right-btn">Gérer</a></h2>
                <div class="voitures-list" style="text-align: left;">
                    <?php foreach ($voitures as $voiture): ?>
                        <div class="voiture-item">
                            <h3><?php echo htmlspecialchars($voiture->marque ?? ''); ?> - <?php echo htmlspecialchars($voiture->modele ?? ''); ?></h3>
                            <p><strong>Immatriculation:</strong> <?php echo htmlspecialchars($voiture->immatriculation ?? ''); ?></p>
                            <p><strong>Energie:</strong> <?php echo htmlspecialchars($voiture->energie ?? ''); ?></p>
                            <p><strong>Couleur:</strong> <?php echo htmlspecialchars($voiture->couleur ?? ''); ?></p>
                            <p><strong>Année:</strong> <?php echo htmlspecialchars($voiture->annee ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Voitures</h2>
                <p class="empty-message">Aucune voiture enregistrée.</p>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Section Avis -->
    <?php if (!empty($avis)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Avis</h2>
                <div class="avis-list" style="text-align: left;">
                    <?php foreach ($avis as $av): ?>
                        <div class="avis-item">
                            <div class="avis-header">
                                <span class="avis-note"><?php echo htmlspecialchars($av->note ?? ''); ?>/5</span>
                            </div>
                            <p class="avis-text"><?php echo htmlspecialchars($av->commentaire ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Avis</h2>
                <p class="empty-message">Aucun avis pour le moment.</p>
            </div>
        </section>
    <?php endif; ?>

    <!-- Section Covoiturages -->
    <?php if (!empty($covoiturages)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Trajets en Covoiturage (en tant que chauffeur) <a href="index.php?p=trajets.nouveau" class="btn btn-sm btn-secondary float-right-btn">Ajouter trajet</a></h2>
                <div class="covoiturages-list" style="text-align: left;">
                    <?php foreach ($covoiturages as $covoiturage): ?>
                        <a href="index.php?p=trajets.edit&id=<?php echo $covoiturage->covoiturage_id; ?>" class="covoiturage-item" style="display:block; text-decoration:none; color:inherit; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                            <h3><?php echo htmlspecialchars($covoiturage->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($covoiturage->lieu_arrivee ?? ''); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($covoiturage->date_depart ?? ''); ?></p>
                            <p><strong>Heure:</strong> <?php echo htmlspecialchars(substr($covoiturage->heure_depart ?? '', 0, 5)); ?></p>
                            <p><strong>Places disponibles:</strong> <?php echo htmlspecialchars($covoiturage->nb_place ?? ''); ?></p>
                            <p><strong>Prix:</strong> <?php echo htmlspecialchars($covoiturage->prix_personne ?? ''); ?> €</p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Trajets en Covoiturage (en tant que chauffeur) <a href="index.php?p=trajets.nouveau" class="btn btn-sm btn-secondary float-right-btn">Ajouter trajet</a></h2>
                <p class="empty-message">Aucun trajet créé pour le moment.</p>
            </div>
        </section>
    <?php endif; ?>

    <!-- Section Réservations -->
    <?php if (!empty($reservations)): ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Réservations (en tant que passager)</h2>
                <div class="reservations-list" style="text-align: left;">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-item">
                            <h3><?php echo htmlspecialchars($reservation->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($reservation->lieu_arrivee ?? ''); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($reservation->date_depart ?? ''); ?></p>
                            <p><strong>Heure:</strong> <?php echo htmlspecialchars($reservation->heure_depart ?? ''); ?></p>
                            <p><strong>Chauffeur :</strong> <?php echo htmlspecialchars($reservation->pseudo ?? ''); ?></p>
                            <p><strong>Prix:</strong> <?php echo htmlspecialchars($reservation->prix_personne ?? ''); ?> €</p>
                            <p><strong>Statut:</strong> <?php echo htmlspecialchars($reservation->statut ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="presentation-section">
            <div class="presentation-content">
                <h2>Mes Réservations (en tant que passager)</h2>
                <p class="empty-message">Aucune réservation pour le moment.</p>
            </div>
        </section>
    <?php endif; ?>

<?php endif; ?>