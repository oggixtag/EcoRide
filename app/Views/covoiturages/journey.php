<?php
echo '<pre>';
var_dump('page(journey): ecoride\app\Views\covoiturages\journey.php');
echo '</pre>';
?>

<?php if (isset($error_form_recherche) && ($error_form_recherche)): ?>

    <?php
    echo '<pre>';
    var_dump('page(journey): $error_form_recherche');
    echo '</pre>';
    ?>

    <div class="ecoride-trip-container">

        <h3 class="section-soustitle">Changer les criteres de recherche</h3>
        <form class="search-form" method="post" action="index.php?p=journey">
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

<?php else: ?>

    <div class="ecoride-trip-container">

        <h2 class="section-title">Section voyages</h2>

        <h3 class="section-soustitle">Changer les criteres de recherche</h3>
        <form class="search-form" method="post" action="index.php?p=journey">
            <input type="text" name="lieu_depart" placeholder="Lieu de départ">
            <input type="text" name="lieu_arrivee" placeholder="Destination">
            <input type="date" name="date">
            <button type="submit">Rechercher</button>
        </form>

        <h3 class="section-soustitle">Prochains</h3>
        <?php if (empty($covoiturages)) { ?>

            <div class="trip-list">

                Aucun voyage prévu pour le moment avec les criteres sélectionnés.
                Neamoins, nous pouvon vous propser ces courses ci-dessous :

                <!-- En-tête du tableau de cartes -->
                <div class="trip-list-header">
                    <div class="header-column driver">Conducteur</div>
                    <div class="header-column route">Trajet & Heures</div>
                    <div class="header-column price">Prix / Places</div>
                    <div class="header-column status">Statut</div>
                    <div class="header-column action">Réserver</div>
                </div>
                <?php
                foreach ($covoiturages_lieu_ou_date as $covoiturage):
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
                            if (!function_exists('remove_accents')) {
                                function remove_accents($str)
                                {
                                    $str = (string) $str;
                                    $map = array(
                                        'à' => 'a',
                                        'á' => 'a',
                                        'â' => 'a',
                                        'ã' => 'a',
                                        'ä' => 'a',
                                        'å' => 'a',
                                        'ç' => 'c',
                                        'è' => 'e',
                                        'é' => 'e',
                                        'ê' => 'e',
                                        'ë' => 'e',
                                        'ì' => 'i',
                                        'í' => 'i',
                                        'î' => 'i',
                                        'ï' => 'i',
                                        'ñ' => 'n',
                                        'ò' => 'o',
                                        'ó' => 'o',
                                        'ô' => 'o',
                                        'õ' => 'o',
                                        'ö' => 'o',
                                        'ù' => 'u',
                                        'ú' => 'u',
                                        'û' => 'u',
                                        'ü' => 'u',
                                        'ý' => 'y',
                                        'ÿ' => 'y',
                                        'À' => 'A',
                                        'Á' => 'A',
                                        'Â' => 'A',
                                        'Ã' => 'A',
                                        'Ä' => 'A',
                                        'Å' => 'A',
                                        'Ç' => 'C',
                                        'È' => 'E',
                                        'É' => 'E',
                                        'Ê' => 'E',
                                        'Ë' => 'E',
                                        'Ì' => 'I',
                                        'Í' => 'I',
                                        'Î' => 'I',
                                        'Ï' => 'I',
                                        'Ñ' => 'N',
                                        'Ò' => 'O',
                                        'Ó' => 'O',
                                        'Ô' => 'O',
                                        'Õ' => 'O',
                                        'Ö' => 'O',
                                        'Ù' => 'U',
                                        'Ú' => 'U',
                                        'Û' => 'U',
                                        'Ü' => 'U',
                                        'Ý' => 'Y'
                                    );
                                    return strtr($str, $map);
                                }
                            }
                            //echo '<p>After remove_accents: ' . strtolower(remove_accents($covoiturage->energie)) . '</p>';
                            $energie_normalized = strtolower(remove_accents($covoiturage->energie));
                            if ($energie_normalized === 'electrique') { ?>
                                <span class="eco-badge"><i class="fa-solid fa-leaf"></i> Écologique</span>
                            <?php } else { ?>
                                <span class="eco-badge standard-badge"><i class="fa-solid fa-car"></i> Standard</span>
                            <?php } ?>
                        </div>

                        <!-- Colonne 2 : Temps & Trajet -->
                        <div class="trip-times">
                            <div class="time-block">
                                <span class="time"><?= htmlspecialchars($covoiturage->heure_depart); ?></span>
                                <span class="city"><?= htmlspecialchars($covoiturage->lieu_depart); ?></span>
                                <span class="date"><?= htmlspecialchars($covoiturage->date_depart); ?></span>
                            </div>

                            <span class="separator">→</span>

                            <div class="time-block arrival">
                                <span class="time"><?= htmlspecialchars($covoiturage->heure_arrivee); ?></span>
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
                            if ($covoiturage->nb_place === 0) { ?>
                                <button class="btn-detail btn-disabled-complete" disabled>Complet</button>
                            <?php } else { ?>
                                <button class="btn-detail" onclick="alert('Détail du trajet '. <?= htmlspecialchars($covoiturage->lieu_depart); ?> .'->' . <?= htmlspecialchars($covoiturage->lieu_arrivee); ?>)">Détail</button>
                            <?php };
                            ?>
                        </div>
                    </div>

                <?php
                endforeach;
                ?>
            </div>
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
                foreach ($covoiturages as $covoiturage):
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
                            if (!function_exists('remove_accents')) {
                                function remove_accents($str)
                                {
                                    $str = (string) $str;
                                    $map = array(
                                        'à' => 'a',
                                        'á' => 'a',
                                        'â' => 'a',
                                        'ã' => 'a',
                                        'ä' => 'a',
                                        'å' => 'a',
                                        'ç' => 'c',
                                        'è' => 'e',
                                        'é' => 'e',
                                        'ê' => 'e',
                                        'ë' => 'e',
                                        'ì' => 'i',
                                        'í' => 'i',
                                        'î' => 'i',
                                        'ï' => 'i',
                                        'ñ' => 'n',
                                        'ò' => 'o',
                                        'ó' => 'o',
                                        'ô' => 'o',
                                        'õ' => 'o',
                                        'ö' => 'o',
                                        'ù' => 'u',
                                        'ú' => 'u',
                                        'û' => 'u',
                                        'ü' => 'u',
                                        'ý' => 'y',
                                        'ÿ' => 'y',
                                        'À' => 'A',
                                        'Á' => 'A',
                                        'Â' => 'A',
                                        'Ã' => 'A',
                                        'Ä' => 'A',
                                        'Å' => 'A',
                                        'Ç' => 'C',
                                        'È' => 'E',
                                        'É' => 'E',
                                        'Ê' => 'E',
                                        'Ë' => 'E',
                                        'Ì' => 'I',
                                        'Í' => 'I',
                                        'Î' => 'I',
                                        'Ï' => 'I',
                                        'Ñ' => 'N',
                                        'Ò' => 'O',
                                        'Ó' => 'O',
                                        'Ô' => 'O',
                                        'Õ' => 'O',
                                        'Ö' => 'O',
                                        'Ù' => 'U',
                                        'Ú' => 'U',
                                        'Û' => 'U',
                                        'Ü' => 'U',
                                        'Ý' => 'Y'
                                    );
                                    return strtr($str, $map);
                                }
                            }
                            //echo '<p>After remove_accents: ' . strtolower(remove_accents($covoiturage->energie)) . '</p>';
                            $energie_normalized = strtolower(remove_accents($covoiturage->energie));
                            if ($energie_normalized === 'electrique') { ?>
                                <span class="eco-badge"><i class="fa-solid fa-leaf"></i> Écologique</span>
                            <?php } else { ?>
                                <span class="eco-badge standard-badge"><i class="fa-solid fa-car"></i> Standard</span>
                            <?php } ?>
                        </div>

                        <!-- Colonne 2 : Temps & Trajet -->
                        <div class="trip-times">
                            <div class="time-block">
                                <span class="time"><?= htmlspecialchars($covoiturage->heure_depart); ?></span>
                                <span class="city"><?= htmlspecialchars($covoiturage->lieu_depart); ?></span>
                                <span class="date"><?= htmlspecialchars($covoiturage->date_depart); ?></span>
                            </div>

                            <span class="separator">→</span>

                            <div class="time-block arrival">
                                <span class="time"><?= htmlspecialchars($covoiturage->heure_arrivee); ?></span>
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
                            if ($covoiturage->nb_place === 0) { ?>
                                <button class="btn-detail btn-disabled-complete" disabled>Complet</button>
                            <?php } else { ?>
                                <button class="btn-detail" onclick="alert('Détail du trajet '. <?= htmlspecialchars($covoiturage->lieu_depart); ?> .'->' . <?= htmlspecialchars($covoiturage->lieu_arrivee); ?>)">Détail</button>
                            <?php };
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
<?php endif; ?>