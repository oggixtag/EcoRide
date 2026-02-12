<?php if (isset($auth_type) && $auth_type === 'visiteur'): ?>
    
    <section class="presentation-section">
        <div class="presentation-content">
            <h1>Finalisation de l'inscription</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($visiteur->pseudo ?? ''); ?> !</p>

            <?php if (isset($statut_mail_id) && $statut_mail_id == 1): ?>
                <!-- STATUT 1: Mail à confirmer -->
                <div class="alert alert-warning mt-20">
                    <p><strong>Mail à confirmer</strong></p>
                    <p>Veuillez cliquer sur le bouton ci-dessous pour valider votre adresse email.</p>
                    <br>
                    <a href="index.php?p=utilisateurs.validerEmail" class="btn btn-primary">Valider votre adresse mail</a>
                </div>
            
            <?php elseif (isset($statut_mail_id) && $statut_mail_id == 2): ?>
                <!-- STATUT 2: Formulaire complet -->
                <h2 class="mt-20">Mes Informations Personnelles</h2>
                <p>Votre email est confirmé. Veuillez compléter votre profil pour accéder à toutes les fonctionnalités.</p>
                
                <form action="index.php?p=utilisateurs.finaliserInscription" method="POST" class="form-utilisateurs form-centered-600">
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
                            <?php 
                                $full_email = $visiteur->email;
                                $parts = explode('@', $full_email);
                                $name = $parts[0];
                                $domain = $parts[1];
                                $masked_name = substr($name, 0, 1) . '***' . substr($name, -1);
                                $masked_email = $masked_name . '@' . $domain;
                            ?>
                            <div class="input-group">
                                <input type="text" id="emailDisplay" class="form-control" value="<?php echo htmlspecialchars($masked_email); ?>" disabled data-full="<?php echo htmlspecialchars($full_email); ?>" data-masked="<?php echo htmlspecialchars($masked_email); ?>">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEmail()" id="btnToggleEmail">Regarder</button>
                            </div>
                            <script>
                                function toggleEmail() {
                                    var input = document.getElementById('emailDisplay');
                                    var btn = document.getElementById('btnToggleEmail');
                                    var current = input.value;
                                    var full = input.getAttribute('data-full');
                                    var masked = input.getAttribute('data-masked');
                                    
                                    if (current === masked) {
                                        input.value = full;
                                        btn.textContent = 'Cacher';
                                    } else {
                                        input.value = masked;
                                        btn.textContent = 'Regarder';
                                    }
                                }
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone * (Chiffres uniquement)</label>
                            <input type="tel" id="telephone" name="telephone" class="form-control" required pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ex: 0612345678">
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
    <?php $is_suspended = !empty($utilisateur->est_suspendu) && $utilisateur->est_suspendu == 1; ?>
    <!-- VIEW UTILISATEUR COMPLET -->
    
    <div class="main-layout dashboard-container">
        
        <!-- SIDEBAR (Navigation interne) -->
        <aside class="sidebar">
            <div class="user-summary">
                <div class="user-avatar-placeholder">
                    <?php echo strtoupper(substr($utilisateur->pseudo ?? 'U', 0, 1)); ?>
                </div>
                <h3><?php echo htmlspecialchars($utilisateur->pseudo ?? ''); ?></h3>
                <p class="role-label">
                    <?php 
                    if ($utilisateur->role_id == 1) echo 'Chauffeur';
                    elseif ($utilisateur->role_id == 3) echo 'Chauffeur-Passanger';
                    else echo 'Passanger';
                    ?>
                </p>
                <?php if ($is_suspended): ?>
                    <div class="badge-suspended">SUSPENDU</div>
                <?php endif; ?>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#infos" class="active">Mes Informations</a></li>
                    <li><a href="#voitures">Mes Voitures</a></li>
                    <li><a href="#trajets">Mes Trajets</a></li>
                    <li><a href="#reservations">Mes Réservations</a></li>
                    <li><a href="#avis">Mes Avis</a></li>
                </ul>
            </nav>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="content-area">
            
            <div class="dashboard-header-mobile">
                <h1>Mon Tableau de Bord</h1>
            </div>

            <!-- Section Informations Personnelles -->
            <section id="infos" class="dashboard-section">
                <div class="section-header">
                    <h2>Mes Informations</h2>
                    <?php if ($is_suspended): ?>
                        <button class="btn btn-secondary btn-suspended" disabled>Modifier</button>
                    <?php else: ?>
                        <a href="index.php?p=utilisateurs.profile.edit" class="btn btn-secondary">Modifier</a>
                    <?php endif; ?>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nom Complet</label>
                        <p><?php echo htmlspecialchars($utilisateur->nom ?? '') . ' ' . htmlspecialchars($utilisateur->prenom ?? ''); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <?php 
                            $full_email_user = $utilisateur->email ?? '';
                            $parts_user = explode('@', $full_email_user);
                            if (count($parts_user) == 2) {
                                $name_user = $parts_user[0];
                                $domain_user = $parts_user[1];
                                $masked_name_user = substr($name_user, 0, 1) . '***' . substr($name_user, -1);
                                $masked_email_user = $masked_name_user . '@' . $domain_user;
                            } else {
                                $masked_email_user = $full_email_user;
                            }
                        ?>
                        <div class="input-group-display" style="display:flex; align-items:center; gap:10px;">
                            <p id="emailUserDisplay" style="margin:0;"><?php echo htmlspecialchars($masked_email_user); ?></p>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleUserEmail()" id="btnToggleUserEmail" style="padding: 2px 8px; font-size: 0.8em;">Regarder</button>
                        </div>
                        <input type="hidden" id="emailUserFull" value="<?php echo htmlspecialchars($full_email_user); ?>">
                        <input type="hidden" id="emailUserMasked" value="<?php echo htmlspecialchars($masked_email_user); ?>">
                    </div>
                    <div class="info-item">
                        <label>Téléphone</label>
                         <?php 
                            $full_phone = $utilisateur->telephone ?? '';
                            $masked_phone = $full_phone;
                            if (strlen($full_phone) >= 4) {
                                $masked_phone = substr($full_phone, 0, 2) . '******' . substr($full_phone, -2);
                            }
                        ?>
                        <div class="input-group-display" style="display:flex; align-items:center; gap:10px;">
                            <p id="phoneUserDisplay" style="margin:0;"><?php echo htmlspecialchars($masked_phone); ?></p>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleUserPhone()" id="btnToggleUserPhone" style="padding: 2px 8px; font-size: 0.8em;">Regarder</button>
                        </div>
                        <input type="hidden" id="phoneUserFull" value="<?php echo htmlspecialchars($full_phone); ?>">
                        <input type="hidden" id="phoneUserMasked" value="<?php echo htmlspecialchars($masked_phone); ?>">
                    </div>
                    <script>
                        function toggleUserEmail() {
                            var display = document.getElementById('emailUserDisplay');
                            var btn = document.getElementById('btnToggleUserEmail');
                            var full = document.getElementById('emailUserFull').value;
                            var masked = document.getElementById('emailUserMasked').value;
                            
                            if (display.textContent === masked) {
                                display.textContent = full;
                                btn.textContent = 'Cacher';
                            } else {
                                display.textContent = masked;
                                btn.textContent = 'Regarder';
                            }
                        }
                        
                        function toggleUserPhone() {
                            var display = document.getElementById('phoneUserDisplay');
                            var btn = document.getElementById('btnToggleUserPhone');
                            var full = document.getElementById('phoneUserFull').value;
                            var masked = document.getElementById('phoneUserMasked').value;
                            
                            if (display.textContent === masked) {
                                display.textContent = full;
                                btn.textContent = 'Cacher';
                            } else {
                                display.textContent = masked;
                                btn.textContent = 'Regarder';
                            }
                        }
                    </script>
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
                        <p class="credit-amount"><?php echo htmlspecialchars($utilisateur->credit ?? 0); ?> </p>
                    </div>
                </div>
            </section>
            
            <!-- Section Voitures -->
            <section id="voitures" class="dashboard-section">
                <div class="section-header">
                    <h2>Mes Voitures</h2>
                    <?php if ($is_suspended): ?>
                        <button class="btn btn-secondary btn-suspended" disabled>Gérer</button>
                    <?php else: ?>
                        <a href="index.php?p=utilisateurs.voitures.index" class="btn btn-secondary">Gérer</a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($voitures)): ?>
                    <div class="voitures-list">
                        <?php foreach ($voitures as $voiture): ?>
                            <div class="voiture-item card-style">
                                <h3><?php echo htmlspecialchars($voiture->marque ?? ''); ?> - <?php echo htmlspecialchars($voiture->modele ?? ''); ?></h3>
                                <p><strong>Immatriculation:</strong> <?php echo htmlspecialchars($voiture->immatriculation ?? ''); ?></p>
                                <p><strong>Energie:</strong> <?php echo htmlspecialchars($voiture->energie ?? ''); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-message">Aucune voiture enregistrée.</p>
                <?php endif; ?>
            </section>

            <!-- Section Trajets -->
            <section id="trajets" class="dashboard-section">
                <div class="section-header">
                    <h2>Mes Trajets (Chauffeur)</h2>
                    <div class="header-actions">
                         <?php if (isset($has_history_chauffeur) && $has_history_chauffeur): ?>
                            <a href="index.php?p=utilisateurs.historique" class="btn btn-history">Historique</a>
                        <?php endif; ?>
                        <?php if ($is_suspended): ?>
                            <button class="btn btn-secondary btn-suspended" disabled>Ajouter</button>
                        <?php else: ?>
                            <a href="index.php?p=trajets.nouveau" class="btn btn-secondary">Ajouter</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($covoiturages)): ?>
                    <div class="covoiturages-list">
                        <?php foreach ($covoiturages as $covoiturage): ?>
                            <a href="index.php?p=trajets.edit&id=<?php echo $covoiturage->covoiturage_id; ?>" class="covoiturage-item card-style">
                                <div class="trip-summary">
                                    <span class="trip-route"><?php echo htmlspecialchars($covoiturage->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($covoiturage->lieu_arrivee ?? ''); ?></span>
                                    <span class="trip-date"><?php echo htmlspecialchars($covoiturage->date_depart ?? ''); ?> à <?php echo htmlspecialchars(substr($covoiturage->heure_depart ?? '', 0, 5)); ?></span>
                                </div>
                                <div class="trip-details">
                                    <span><?php echo htmlspecialchars($covoiturage->nb_place ?? ''); ?> places</span>
                                    <span class="trip-price"><?php echo htmlspecialchars($covoiturage->prix_personne ?? ''); ?> </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-message">Aucun trajet à venir.</p>
                <?php endif; ?>
            </section>

             <!-- Section Réservations -->
            <section id="reservations" class="dashboard-section">
                <div class="section-header">
                    <h2>Mes Réservations</h2>
                    <?php if (isset($has_history_passager) && $has_history_passager): ?>
                        <a href="index.php?p=utilisateurs.historique" class="btn btn-history">Historique</a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($reservations)): ?>
                    <div class="reservations-list">
                        <?php foreach ($reservations as $reservation): ?>
                            <div class="reservation-item card-style">
                                <div class="trip-summary">
                                    <span class="trip-route"><?php echo htmlspecialchars($reservation->lieu_depart ?? ''); ?> → <?php echo htmlspecialchars($reservation->lieu_arrivee ?? ''); ?></span>
                                    <span class="trip-date"><?php echo htmlspecialchars($reservation->date_depart ?? ''); ?></span>
                                </div>
                                <p class="driver-name">Chauffeur: <?php echo htmlspecialchars($reservation->pseudo ?? ''); ?></p>
                                <span class="status-badge status-<?php echo strtolower($reservation->statut ?? ''); ?>"><?php echo htmlspecialchars($reservation->statut ?? ''); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-message">Aucune réservation active.</p>
                <?php endif; ?>
            </section>
            
             <!-- Section Avis -->
            <section id="avis" class="dashboard-section">
                <div class="section-header">
                    <h2>Mes Avis</h2>
                </div>
                 <?php if (!empty($avis)): ?>
                    <div class="avis-list">
                        <?php foreach ($avis as $av): ?>
                            <div class="avis-item card-style">
                                <div class="avis-header">
                                    <span class="avis-note"><?php echo htmlspecialchars($av->note ?? ''); ?>/5</span>
                                </div>
                                <p class="avis-text"><?php echo htmlspecialchars($av->commentaire ?? ''); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-message">Aucun avis reçu.</p>
                <?php endif; ?>
            </section>

        </div> <!-- Fin Content Area -->
    </div> <!-- Fin Main Layout -->

<?php endif; ?>