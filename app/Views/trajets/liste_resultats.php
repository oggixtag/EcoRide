<?php
// Déterminer quelle liste afficher
$has_trajets = isset($trajets) && !empty($trajets);
$has_suggestions = isset($trajet_lieu_ou_date) && !empty($trajet_lieu_ou_date);

if ($has_trajets) {
    // Afficher les résultats exacts
    ?>
    <div class="trip-list-header">
        <div class="header-column driver">Conducteur</div>
        <div class="header-column route">Itinéraire & Horaires</div>
        <div class="header-column price">Prix / Places</div>
        <div class="header-column status">Statut</div>
        <div class="header-column action">Réserver</div>
    </div>
    <?php foreach ($trajets as $covoiturage): ?>
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
                // Besoin de récupérer $utilisateur_courant et $participations_utilisateur s'ils sont extraits
                $utilisateur_courant = isset($utilisateur_courant) ? $utilisateur_courant : null;
                $participations_utilisateur = isset($participations_utilisateur) ? $participations_utilisateur : array();
                
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
    <?php endforeach; ?>
<?php
} elseif ($has_suggestions) {
    // Afficher les suggestions
    ?>
    <div class="alert alert-info">
        Aucun voyage prévu pour le moment avec les critères exacts.
        Néanmoins, nous pouvons vous proposer ces courses ci-dessous.
    </div>
    <div class="trip-list-header">
        <div class="header-column driver">Conducteur</div>
        <div class="header-column route">Itinéraire & Horaires</div>
        <div class="header-column price">Prix / Places</div>
        <div class="header-column status">Statut</div>
        <div class="header-column action">Réserver</div>
    </div>
    <?php foreach ($trajet_lieu_ou_date as $covoiturage): ?>
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
                // Re-récupérer variables si nécessaire (déjà fait au début du fichier ?) Non, c'est une boucle différente.
                // On a accès aux variables extraites.
                $utilisateur_courant = isset($utilisateur_courant) ? $utilisateur_courant : null;
                $participations_utilisateur = isset($participations_utilisateur) ? $participations_utilisateur : array();

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
    <?php endforeach; ?>
<?php
} else {
    // Aucun résultat
    ?>
    <div class="alert alert-warning">
        Aucun résultat trouvé pour votre recherche.
    </div>
<?php
}
?>
