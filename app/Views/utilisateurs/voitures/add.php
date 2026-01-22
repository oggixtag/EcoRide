<section class="presentation-section">
    <div class="presentation-content">
        <div class="dashboard-header" style="background: none; color: inherit; padding: 0; box-shadow: none;">
            <h1>Ajouter une voiture</h1>
        </div>
    </div>
</section>

<?php if (!empty($message)): ?>
    <section class="presentation-section" style="padding: 15px; margin: 10px auto;">
        <div class="alert alert-<?= $message_type ?>" style="width: 100%; margin: 0;">
            <?= $message ?>
        </div>
    </section>
<?php endif; ?>

<section class="presentation-section">
    <div class="presentation-content">
        <form action="index.php?p=utilisateurs.voitures.add" method="POST" class="form-utilisateurs" style="text-align: left; max-width: 800px; margin: 0 auto;">
            
            <div class="form-group">
                <label for="marque_id">Marque *</label>
                <select id="marque_id" name="marque_id" class="form-control" required>
                    <option value="">Sélectionner une marque</option>
                    <?php foreach ($marques as $marque): ?>
                        <option value="<?= $marque->marque_id ?>"><?= htmlspecialchars($marque->libelle) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="modele">Modèle *</label>
                <input type="text" id="modele" name="modele" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="immatriculation">Immatriculation *</label>
                <input type="text" id="immatriculation" name="immatriculation" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="energie">Energie *</label>
                <select id="energie" name="energie" class="form-control" required>
                    <option value="Essence">Essence</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Hybride">Hybride</option>
                    <option value="Électrique">Électrique</option>
                </select>
            </div>

            <div class="form-group">
                <label for="couleur">Couleur</label>
                <input type="text" id="couleur" name="couleur" class="form-control">
            </div>

            <div class="form-group">
                <label for="date_premiere_immatriculation">Date de 1ère Immatriculation</label>
                <input type="date" id="date_premiere_immatriculation" name="date_premiere_immatriculation" class="form-control">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <a href="index.php?p=utilisateurs.voitures.index" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
