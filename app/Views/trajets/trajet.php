<?php if (isset($error_form_recherche) && ($error_form_recherche)): ?>

    <section class="presentation-section">
        <div class="presentation-content">
            <h3 class="section-soustitle">Changer les criteres de recherche</h3>
            <form class="search-form" method="post" action="index.php?p=trajet">
                <input type="text" name="lieu_depart" placeholder="Lieu de départ">
                <input type="text" name="lieu_arrivee" placeholder="Destination">
                <input type="date" name="date">
                <button type="submit">Rechercher</button>
            </form>

            <h3 class="section-soustitle">Prochains</h3>
            <div class="alert alert-danger">
                Inserer au moins le lieu de départ et la date
            </div>
            <p>
                <a href="index.php">Retour à la page d'accueil</a>
            </p>
        </div>
    </section>

<?php else: ?>

    <section class="presentation-section">
        <div class="presentation-content">
            <h2 class="section-title">Section voyages</h2>

            <h3 class="section-soustitle">Changer les criteres de recherche</h3>
            <form class="search-form" method="post" action="index.php?p=trajet">
                <input type="text" name="lieu_depart" placeholder="Lieu de départ">
                <input type="text" name="lieu_arrivee" placeholder="Destination">
                <input type="date" name="date">
                <button type="submit">Rechercher</button>
            </form>

            <?php if (empty($trajets)) { ?>




                <!--<div class="trip-list">-->

                Aucun voyage prévu pour le moment avec les criteres sélectionnés.
                Neamoins, nous pouvon vous propser ces courses ci-dessous.

                <h3 class="section-soustitle">Filtres courses</h3>

                <!-- SECTION FILTRES -->
                <section class="filters-bar">

                    <form method="post" action="index.php?p=trajet" id="form-filters" name="filters-form">
                        <!-- Champs cachés pour conserver les critères de recherche principaux -->
                        <input type="hidden" name="lieu_depart" value="<?= htmlspecialchars($_POST['lieu_depart'] ?? ''); ?>">
                        <input type="hidden" name="date" value="<?= htmlspecialchars($_POST['date'] ?? ''); ?>">
                        <!-- Flag pour indiquer que les filtres doivent être appliqués 
                    <input type="hidden" name="apply_filters" value="1">-->
                        <!-- Type de Voyage -->
                        <div class="filter-group">
                            <label>TYPE DE VOYAGE</label>
                            <div class="mention-options">
                                <label class="option-item">
                                    <input type="checkbox" name="energie[]" value="ecologique"
                                        <?= (isset($_POST['energie']) && is_array($_POST['energie']) && in_array('ecologique', $_POST['energie'])) ? 'checked' : ''; ?>>
                                    <span>ÉCO</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="energie[]" value="standard"
                                        <?= (isset($_POST['energie']) && is_array($_POST['energie']) && in_array('standard', $_POST['energie'])) ? 'checked' : ''; ?>>
                                    <span>STD</span>
                                </label>
                            </div>
                        </div>
                        <!-- Prix Min -->
                        <div class="filter-group">
                            <label>PRIX MIN (<span id="label-prix-min"><?= htmlspecialchars($_POST['prix_min'] ?? '0'); ?></span>€)</label>
                            <input type="range" class="range-slider" name="prix_min" id="prix_min" min="0" max="50" value="<?= htmlspecialchars($_POST['prix_min'] ?? '0'); ?>">
                            <div class="range-values"><span>0€</span><span>50€</span></div>
                        </div>
                        <!-- Prix Max -->
                        <div class="filter-group">
                            <label>PRIX MAX (<span id="label-prix-max"><?= htmlspecialchars($_POST['prix_max'] ?? '50'); ?></span>€)</label>
                            <input type="range" class="range-slider" name="prix_max" id="prix_max" min="5" max="100" value="<?= htmlspecialchars($_POST['prix_max'] ?? '50'); ?>">
                            <div class="range-values"><span>5€</span><span>100€</span></div>
                        </div>
                        <!-- Durée Max -->
                        <div class="filter-group">
                            <label>DURÉE MAX (<span id="label-duree"><?= htmlspecialchars($_POST['duree_max'] ?? '20'); ?></span>h)</label>
                            <input type="range" class="range-slider" name="duree_max" id="duree_max" min="1" max="20" value="<?= htmlspecialchars($_POST['duree_max'] ?? '20'); ?>">
                            <div class="range-values"><span>1h</span><span>20h</span></div>
                        </div>
                        <!-- Notes -->
                        <div class="filter-group">
                            <label>NOTES (<span id="label-score"><?= htmlspecialchars($_POST['score_min'] ?? '4'); ?></span>★)</label>
                            <input type="range" class="range-slider" name="score_min" id="score_min" min="1" max="5" step="0.1" value="<?= htmlspecialchars($_POST['score_min'] ?? '4'); ?>">
                            <div class="range-values"><span>1★</span><span>5★</span></div>
                        </div>
                        <!-- Boutons -->
                        <div class="filter-actions">
                            <button type="button" class="btn-filter btn-reset">Réinitialiser</button>
                            <button type="submit" class="btn-filter btn-apply">Appliquer</button>
                        </div>
                    </form>
                </section>

                <!-- En-tête du tableau de cartes -->
                <div class="trip-list-header">
                    <div class="header-column driver">Conducteur</div>
                    <div class="header-column route">Itinéraire & Horaires</div>
                    <div class="header-column price">Prix / Places</div>
                    <div class="header-column status">Statut</div>
                    <div class="header-column action">Réserver</div>
                </div>
                <?php
                foreach ($trajet_lieu_ou_date as $covoiturage):
                ?>

                    <!-- Seuls les itinéraires avec au minimum une place disponible sont proposées -->

                    <!-- Carte de Covoiturage -->
                    <div class="trip-card">
                        <!-- Colonne 1 : Chauffeur & Type de chauffeur -->
                        <div class="trip-driver-info">
                            <div class="driver-profile">
                                <img class="driver-photo" src="https://placehold.co/60x60/4CAF50/ffffff?text=JM" alt="Photo Chauffeur">
                                <div class="driver-details-text">
                                    <span class="driver-name"><?= htmlspecialchars($covoiturage->pseudo); ?></span>
                                    <span class="driver-rating"><i class="fa-solid fa-star"></i> 4.8/5</span>
                                </div>
                            </div>
                            <!-- Mention écologique (badge en vert primaire) 
                                    Un voyage est considéré écologique s’il est effectué avec une voiture électrique.
                                -->
                            <?php
                            $energie_normalized = $energie_normalized_map[$covoiturage->covoiturage_id] ?? 'standard';
                            if ($energie_normalized === 'electrique') { ?>
                                <span class="eco-badge"><i class="fa-solid fa-leaf"></i> Écologique</span>
                            <?php } else { ?>
                                <span class="eco-badge standard-badge"><i class="fa-solid fa-car"></i> Standard</span>
                            <?php } ?>
                        </div>

                        <!-- Colonne 2 : Temps & Trajet -->
                        <div class="trip-times">
                            <div class="time-block">
                                <span class="time"><?= htmlspecialchars(substr($covoiturage->heure_depart, 0, 5)); ?></span>
                                <span class="city"><?= htmlspecialchars($covoiturage->lieu_depart); ?></span>
                                <span class="date"><?= htmlspecialchars($covoiturage->date_depart); ?></span>
                            </div>

                            <span class="separator">→</span>

                            <div class="time-block arrival">
                                <span class="time"><?= htmlspecialchars(substr($covoiturage->heure_arrivee, 0, 5)); ?></span>
                                <span class="city"><?= htmlspecialchars($covoiturage->lieu_arrivee); ?></span>
                                <span class="date" style="visibility: hidden;">Placeholder</span>
                            </div>
                        </div>

                        <!-- Colonne 3 : Prix & Places -->
                        <div class="trip-pricing">
                            <span class="trip-price"><?= htmlspecialchars($covoiturage->prix_personne); ?> €</span>
                            <span class="remaining-seats"><i class="fa-solid fa-chair"></i> <?= htmlspecialchars($covoiturage->nb_place); ?> places restantes </span>
                        </div>

                        <!-- Colonne 4 : Statut -->
                        <div class="trip-status">
                            <span class="status-confirmed"><?= htmlspecialchars($covoiturage->statut); ?></span>
                        </div>

                        <!-- Colonne 5 : Action -->
                        <div class="trip-action">
                            <?php
                            // Déterminer la visibilité du bouton Détail
                            $estConnecte = isset($_SESSION['auth']);
                            $creditSuffisant = $estConnecte ? (isset($utilisateur_courant) && $utilisateur_courant && $utilisateur_courant->credit > 0) : true;
                            $placesDisponibles = $covoiturage->nb_place > 0;
                            $dejaReserve = isset($participations_utilisateur[$covoiturage->covoiturage_id]) && $participations_utilisateur[$covoiturage->covoiturage_id];

                            // Afficher le bouton approprié selon les conditions
                            if ($covoiturage->nb_place === 0) {
                                // Toujours afficher Complet si pas de places
                            ?>
                                <button class="btn-detail btn-disabled-complete" disabled>Complet</button>
                                <?php
                            } elseif ($estConnecte) {
                                // Utilisateur connecté
                                if ($dejaReserve) {
                                    // Utilisateur a déjà réservé : afficher bouton grisé "Réservé"
                                ?>
                                    <button class="btn-detail btn-disabled-reserved" disabled>Réservé</button>
                                <?php
                                } elseif (!$creditSuffisant) {
                                    // Utilisateur n'a pas assez de crédit : afficher bouton grisé
                                ?>
                                    <button class="btn-detail btn-disabled-complete" disabled>Crédit insuffisant</button>
                                <?php
                                } else {
                                    // Utilisateur connecté avec crédit : afficher Détail
                                ?>
                                    <a href="index.php?p=trajet-detail&id=<?= htmlspecialchars($covoiturage->covoiturage_id); ?>" class="btn-detail">Détail</a>
                                <?php
                                }
                            } else {
                                // Utilisateur non connecté : toujours afficher Détail si places
                                ?>
                                <a href="index.php?p=trajet-detail&id=<?= htmlspecialchars($covoiturage->covoiturage_id); ?>" class="btn-detail">Détail</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                <?php
                endforeach;
                ?>
                <!-- </div>-->
                <a href="index.php">Retour à la page d'accueil</a>



            <?php } else { ?>
                <div class="trip-list">

                    <!-- En-tête du tableau de cartes -->
                    <div class="trip-list-header">
                        <div class="header-column driver">Conducteur</div>
                        <div class="header-column route">Trajet & Heures</div>
                        <div class="header-column price">Prix / Places</div>
                        <div class="header-column status">Statut</div>
                        <div class="header-column action">Réserver</div>
                    </div>
                    <?php
                    foreach ($trajets as $covoiturage):
                    ?>

                        <!-- Seuls les itinéraires avec au minimum une place disponible sont proposées -->

                        <!-- Carte de Covoiturage -->
                        <div class="trip-card">
                            <!-- Colonne 1 : Chauffeur & Type de chauffeur -->
                            <div class="trip-driver-info">
                                <div class="driver-profile">
                                    <img class="driver-photo" src="https://placehold.co/60x60/4CAF50/ffffff?text=JM" alt="Photo Chauffeur">
                                    <div class="driver-details-text">
                                        <span class="driver-name"><?= htmlspecialchars($covoiturage->pseudo); ?></span>
                                        <span class="driver-rating"><i class="fa-solid fa-star"></i> 4.8/5</span>
                                    </div>
                                </div>
                                <!-- Mention écologique (badge en vert primaire) 
                                    Un voyage est considéré écologique s’il est effectué avec une voiture électrique.
                                -->
                                <?php
                                $energie_normalized = $energie_normalized_map[$covoiturage->covoiturage_id] ?? 'standard';
                                if ($energie_normalized === 'electrique') { ?>
                                    <span class="eco-badge"><i class="fa-solid fa-leaf"></i> Écologique</span>
                                <?php } else { ?>
                                    <span class="eco-badge standard-badge"><i class="fa-solid fa-car"></i> Standard</span>
                                <?php } ?>
                            </div>

                            <!-- Colonne 2 : Temps & Trajet -->
                            <div class="trip-times">
                                <div class="time-block">
                                    <span class="time"><?= htmlspecialchars(substr($covoiturage->heure_depart, 0, 5)); ?></span>
                                    <span class="city"><?= htmlspecialchars($covoiturage->lieu_depart); ?></span>
                                    <span class="date"><?= htmlspecialchars($covoiturage->date_depart); ?></span>
                                </div>

                                <span class="separator">→</span>

                                <div class="time-block arrival">
                                    <span class="time"><?= htmlspecialchars(substr($covoiturage->heure_arrivee, 0, 5)); ?></span>
                                    <span class="city"><?= htmlspecialchars($covoiturage->lieu_arrivee); ?></span>
                                    <span class="date" style="visibility: hidden;">Placeholder</span>
                                </div>
                            </div>

                            <!-- Colonne 3 : Prix & Places -->
                            <div class="trip-pricing">
                                <span class="trip-price"><?= htmlspecialchars($covoiturage->prix_personne); ?> €</span>
                                <span class="remaining-seats"><i class="fa-solid fa-chair"></i> <?= htmlspecialchars($covoiturage->nb_place); ?> places restantes </span>
                            </div>

                            <!-- Colonne 4 : Statut -->
                            <div class="trip-status">
                                <span class="status-confirmed"><?= htmlspecialchars($covoiturage->statut); ?></span>
                            </div>

                            <!-- Colonne 5 : Action -->
                            <div class="trip-action">
                                <?php
                                // Déterminer la visibilité du bouton Détail
                                $estConnecte = isset($_SESSION['auth']);
                                $creditSuffisant = $estConnecte ? (isset($utilisateur_courant) && $utilisateur_courant && $utilisateur_courant->credit > 0) : true;
                                $placesDisponibles = $covoiturage->nb_place > 0;
                                $dejaReserve = isset($participations_utilisateur[$covoiturage->covoiturage_id]) && $participations_utilisateur[$covoiturage->covoiturage_id];

                                // Afficher le bouton approprié selon les conditions
                                if ($covoiturage->nb_place === 0) {
                                    // Toujours afficher Complet si pas de places
                                ?>
                                    <button class="btn-detail btn-disabled-complete" disabled>Complet</button>
                                    <?php
                                } elseif ($estConnecte) {
                                    // Utilisateur connecté
                                    if ($dejaReserve) {
                                        // Utilisateur a déjà réservé : afficher bouton grisé "Réservé"
                                    ?>
                                        <button class="btn-detail btn-disabled-reserved" disabled>Réservé</button>
                                    <?php
                                    } elseif (!$creditSuffisant) {
                                        // Utilisateur n'a pas assez de crédit : afficher bouton grisé
                                    ?>
                                        <button class="btn-detail btn-disabled-complete" disabled>Crédit insuffisant</button>
                                    <?php
                                    } else {
                                        // Utilisateur connecté avec crédit : afficher Détail
                                    ?>
                                        <a href="index.php?p=trajet-detail&id=<?= htmlspecialchars($covoiturage->covoiturage_id); ?>" class="btn-detail">Détail</a>
                                    <?php
                                    }
                                } else {
                                    // Utilisateur non connecté : toujours afficher Détail si places
                                    ?>
                                    <a href="index.php?p=trajet-detail&id=<?= htmlspecialchars($covoiturage->covoiturage_id); ?>" class="btn-detail">Détail</a>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                    <?php
                    endforeach;
                    ?>
                </div>
                <a href="index.php">Retour à la page d'accueil</a>
            <?php } ?>
        </div>
    </section>

<?php endif; ?>