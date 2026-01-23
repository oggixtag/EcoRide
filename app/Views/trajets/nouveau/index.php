<section class="presentation-section">
    <div class="presentation-content">
        <div class="dashboard-header" style="background: none; color: inherit; padding: 0; box-shadow: none;">
            <h1>Saisir un nouveau voyage</h1>
        </div>
    </div>
</section>

<?php if (isset($_SESSION['flash_message'])): ?>
    <section class="presentation-section" style="padding: 15px; margin: 10px auto;">
        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?>" style="width: 100%;">
            <?= $_SESSION['flash_message'] ?>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        </div>
    </section>
<?php endif; ?>

<section class="presentation-section">
    <div class="presentation-content">
        <div class="alert alert-info" style="margin-bottom: 20px; text-align: left;">
            <p><strong>Note :</strong> La création d'un voyage coûte <strong>2 crédits</strong>.</p>
            <p>Vos crédits actuels : <strong><?= htmlspecialchars($utilisateur->credit) ?></strong></p>
        </div>

        <form action="index.php?p=trajets.sauvegarder" method="POST" class="form-utilisateurs" style="text-align: left; max-width: 800px; margin: 0 auto;">
            
            <div class="form-group-row">
                <div class="form-group">
                    <label for="lieu_depart">Lieu de départ *</label>
                    <input type="text" id="lieu_depart" name="lieu_depart" class="form-control" required placeholder="Ex: Paris">
                </div>
                <div class="form-group">
                    <label for="lieu_arrivee">Lieu d'arrivée *</label>
                    <input type="text" id="lieu_arrivee" name="lieu_arrivee" class="form-control" required placeholder="Ex: Lyon">
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="date_depart">Date de départ *</label>
                    <input type="date" id="date_depart" name="date_depart" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="heure_depart">Heure de départ *</label>
                    <input type="time" id="heure_depart" name="heure_depart" class="form-control" required>
                </div>
            </div>

            <!-- Optional Date/Heure Arrivee if needed, usually calculated or optional. Added for completeness based on DB -->
             <div class="form-group-row">
                <div class="form-group">
                    <label for="date_arrivee">Date d'arrivée (optionnel)</label>
                    <input type="date" id="date_arrivee" name="date_arrivee" class="form-control">
                    <small>Laisser vide si le même jour</small>
                </div>
                <div class="form-group">
                    <label for="heure_arrivee">Heure d'arrivée (estimation) *</label>
                    <input type="time" id="heure_arrivee" name="heure_arrivee" class="form-control" required>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="prix_personne">Prix par personne (€) *</label>
                    <input type="number" id="prix_personne" name="prix_personne" class="form-control" min="0" step="0.5" required>
                </div>
                <div class="form-group">
                    <label for="nb_place">Places disponibles *</label>
                    <input type="number" id="nb_place" name="nb_place" class="form-control" min="1" max="9" required>
                </div>
            </div>

            <div class="form-group">
                <label for="voiture_id">Véhicule *</label>
                <select id="voiture_id" name="voiture_id" class="form-control" required>
                    <option value="">Sélectionnez un véhicule</option>
                    <?php foreach ($voitures as $voiture): ?>
                        <option value="<?= $voiture->voiture_id ?>">
                            <?= htmlspecialchars($voiture->marque . ' ' . $voiture->modele . ' (' . $voiture->immatriculation . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div style="margin-top: 5px;">
                    <a href="index.php?p=utilisateurs.voitures.add" target="_blank" style="font-size: 0.9em;">+ Ajouter un nouveau véhicule</a>
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Publier le trajet</button>
                <a href="index.php?p=utilisateurs.profile.index" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
