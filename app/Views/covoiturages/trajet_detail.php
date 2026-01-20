<section class="presentation-section">
    <div class="presentation-content">

    <?php if (isset($covoiturage)): ?>

        <!-- Section Itinéraire -->
        <section class="details-section itinerary-section">
            <h2 class="section-title"><i class="fa-solid fa-map"></i> Itinéraire & Horaires</h2>
            <div class="itinerary-container">
                <div class="journey-point departure">
                    <div class="time"><?= htmlspecialchars(substr($covoiturage->heure_depart, 0, 5)); ?></div>
                    <div class="city"><?= htmlspecialchars($covoiturage->lieu_depart); ?></div>
                    <div class="date"><?= htmlspecialchars($covoiturage->date_depart); ?></div>
                </div>

                <div class="journey-arrow">
                    <span class="arrow">→</span>
                    <span class="duration" id="journey-duration">Durée</span>
                </div>

                <div class="journey-point arrival">
                    <div class="time"><?= htmlspecialchars(substr($covoiturage->heure_arrivee, 0, 5)); ?></div>
                    <div class="city"><?= htmlspecialchars($covoiturage->lieu_arrivee); ?></div>
                    <div class="date"><?= htmlspecialchars($covoiturage->date_arrivee); ?></div>
                </div>
            </div>
        </section>

        <!-- Section Conducteur & Avis -->
        <section class="details-section driver-section">
            <h2 class="section-title"><i class="fa-solid fa-user-circle"></i> À propos du Conducteur</h2>

            <div class="driver-card">
                <div class="driver-header">
                    <img class="driver-avatar" src="https://placehold.co/80x80/4CAF50/ffffff?text=<?= htmlspecialchars(substr($covoiturage->pseudo, 0, 2)); ?>" alt="<?= htmlspecialchars($covoiturage->pseudo); ?>">
                    <div class="driver-info">
                        <h3 class="driver-name"><?= htmlspecialchars($covoiturage->pseudo); ?></h3>
                        <div class="driver-rating">
                            <span class="stars">
                                <?php
                                $rating = isset($covoiturage->avis) && !empty($covoiturage->avis)
                                    ? array_sum(array_map(function ($a) {
                                        return floatval($a->note);
                                    }, $covoiturage->avis)) / count($covoiturage->avis)
                                    : 0;
                                $rating = round($rating, 1);
                                for ($i = 0; $i < 5; $i++) {
                                    if ($i < floor($rating)) {
                                        echo '<i class="fa-solid fa-star filled"></i>';
                                    } elseif ($i < $rating) {
                                        echo '<i class="fa-solid fa-star-half-stroke filled"></i>';
                                    } else {
                                        echo '<i class="fa-solid fa-star"></i>';
                                    }
                                }
                                ?>
                            </span>
                            <span class="rating-value"><?= htmlspecialchars($rating); ?>/5</span>
                        </div>
                        <p class="member-since">Membre depuis 2023</p>
                    </div>
                </div>

                <!-- Avis du conducteur -->
                <div class="reviews-container">
                    <h3 class="subsection-title">Avis (<?= isset($covoiturage->avis) ? count($covoiturage->avis) : 0; ?>)</h3>
                    <?php if (isset($covoiturage->avis) && !empty($covoiturage->avis)): ?>
                        <div class="reviews-list">
                            <?php foreach ($covoiturage->avis as $avis): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-rating">
                                            <?php
                                            $note = floatval($avis->note);
                                            for ($i = 0; $i < 5; $i++) {
                                                if ($i < floor($note)) {
                                                    echo '<i class="fa-solid fa-star filled"></i>';
                                                } elseif ($i < $note) {
                                                    echo '<i class="fa-solid fa-star-half-stroke filled"></i>';
                                                } else {
                                                    echo '<i class="fa-solid fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <span class="review-date"><?= htmlspecialchars($avis->statut ?? 'Positif'); ?></span>
                                    </div>
                                    <p class="review-comment"><?= htmlspecialchars($avis->commentaire ?? ''); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-reviews">Aucun avis pour le moment</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Section Véhicule -->
        <section class="details-section vehicle-section">
            <h2 class="section-title"><i class="fa-solid fa-car"></i> Véhicule</h2>

            <div class="vehicle-card">
                <div class="vehicle-info-grid">
                    <div class="vehicle-info-item">
                        <span class="label">Marque</span>
                        <span class="value"><?= htmlspecialchars($covoiturage->marque); ?></span>
                    </div>
                    <div class="vehicle-info-item">
                        <span class="label">Modèle</span>
                        <span class="value"><?= htmlspecialchars($covoiturage->modele); ?></span>
                    </div>
                    <div class="vehicle-info-item">
                        <span class="label">Énergie</span>
                        <span class="value">
                            <?php
                            if ($energie_normalized === 'electrique') {
                                echo '<span class="eco-badge"><i class="fa-solid fa-leaf"></i> Écologique</span>';
                            } else {
                                echo '<span class="eco-badge standard-badge"><i class="fa-solid fa-car"></i> Standard</span>';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="vehicle-info-item">
                        <span class="label">Couleur</span>
                        <span class="value"><?= htmlspecialchars($covoiturage->couleur ?? 'Non spécifiée'); ?></span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Prix & Places -->
        <section class="details-section pricing-section">
            <h2 class="section-title"><i class="fa-solid fa-euro-sign"></i> Tarification & Disponibilités</h2>

            <div class="pricing-grid">
                <div class="pricing-item">
                    <span class="label">Prix par personne</span>
                    <span class="price-value"><?= htmlspecialchars($covoiturage->prix_personne); ?> €</span>
                </div>
                <div class="pricing-item">
                    <span class="label">Places disponibles</span>
                    <span class="value">
                        <?php
                        $nb_place = intval($covoiturage->nb_place);
                        if ($nb_place > 0) {
                            echo '<span class="seats-available"><i class="fa-solid fa-chair"></i> ' . htmlspecialchars($nb_place) . ' place' . ($nb_place > 1 ? 's' : '') . '</span>';
                        } else {
                            echo '<span class="seats-full"><i class="fa-solid fa-ban"></i> Complet</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="pricing-item">
                    <span class="label">Statut</span>
                    <span class="value"><?= htmlspecialchars($covoiturage->statut); ?></span>
                </div>
            </div>
        </section>

        <!-- Section Actions -->
        <section class="details-section actions-section">
            <h2 class="section-title"><i class="fa-solid fa-calendar-check"></i> Actions</h2>
            <div class="action-buttons">
                <?php
                $nb_place = intval($covoiturage->nb_place);
                if ($nb_place > 0) {
                    // Formulaire pour participer au covoiturage
                ?>
                    <form method="POST" id="formParticiper">
                        <input type="hidden" name="covoiturage_id" value="<?= htmlspecialchars($covoiturage->covoiturage_id) ?>">
                        <button type="submit" class="btn-action btn-reserve">
                            <i class="fa-solid fa-check"></i> Participer / Réserver une place
                        </button>
                    </form>
                <?php
                } else {
                    echo '<button class="btn-action btn-disabled" disabled><i class="fa-solid fa-ban"></i> Complet</button>';
                }
                ?>
                <button class="btn-action btn-contact"><i class="fa-solid fa-envelope"></i> Contacter le conducteur</button>
            </div>
        </section>

        <!-- Lien de retour -->
        <section class="details-section return-section">
            <a href="index.php?p=trajet" class="btn-back">← Retour à la liste des trajets</a>
        </section>

    <?php else: ?>
        <div class="error-message">
            <p>Impossible de charger les détails du covoiturage.</p>
            <p><a href="index.php?p=trajet">Retour à la liste des trajets</a></p>
        </div>
    <?php endif; ?>

    </div>
</section>

<!-- Script pour calculer la durée du voyage et gérer la participation -->
<script>
    /**
     * Fonction pour confirmer la participation à un covoiturage
     * Affiche un message de confirmation et traite la requête en AJAX
     */
    function confirmerParticipation(event) {
        event.preventDefault();

        if (!confirm('Êtes-vous sûr de vouloir réserver une place ? 2 crédits seront déduits de votre compte.')) {
            return false;
        }

        // Récupérer le formulaire
        const form = document.getElementById('formParticiper');
        const formData = new FormData(form);

        // Envoyer la requête AJAX
        fetch('index.php?p=covoiturages.participer', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher le message de succès
                    alert(data.message || 'Réservation effectuée avec succès !');

                    // Rediriger vers le dashbord de l'utilisateur
                    setTimeout(() => {
                        window.location.href = 'index.php?p=utilisateurs.index';
                    }, 500);
                } else {
                    // Vérifier s'il faut rediriger vers la connexion
                    if (data.redirect) {
                        alert(data.message || 'Vous devez être connecté');
                        window.location.href = data.redirect;
                    } else {
                        // Afficher le message d'erreur
                        alert(data.message || 'Une erreur s\'est produite lors de la réservation');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur s\'est produite lors de la communication avec le serveur');
            });

        return false; // Empêcher l'envoi du formulaire traditionnel
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Attacher l'événement de soumission du formulaire
        const form = document.getElementById('formParticiper');
        if (form) {
            form.addEventListener('submit', confirmerParticipation);
        }

        <?php if (isset($covoiturage)): ?>
            const departStr = '<?= htmlspecialchars($covoiturage->date_depart . ' ' . $covoiturage->heure_depart); ?>';
            const arriveeStr = '<?= htmlspecialchars($covoiturage->date_arrivee . ' ' . $covoiturage->heure_arrivee); ?>';

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

                const durationEl = document.getElementById('journey-duration');
                if (durationEl) {
                    durationEl.textContent = dureeTexte || '0min';
                }
            }
        <?php endif; ?>
    });
</script>