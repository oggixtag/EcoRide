<section class="presentation-section">
    <div class="presentation-content">
        <div class="dashboard-header" style="background: none; color: inherit; padding: 0; box-shadow: none;">
            <h1>Modifier le trajet</h1>
        </div>
    </div>
</section>

<section class="presentation-section">
    <div class="presentation-content">
        <form action="index.php?p=trajets.update" method="POST" class="form-utilisateurs" style="text-align: left; max-width: 800px; margin: 0 auto;">
            <input type="hidden" name="covoiturage_id" value="<?= $trajet->covoiturage_id ?>">

            <div class="form-group-row">
                <div class="form-group">
                    <label for="lieu_depart">Lieu de départ *</label>
                    <input type="text" id="lieu_depart" name="lieu_depart" class="form-control" value="<?= htmlspecialchars($trajet->lieu_depart) ?>" required>
                </div>
                <div class="form-group">
                    <label for="lieu_arrivee">Lieu d'arrivée *</label>
                    <input type="text" id="lieu_arrivee" name="lieu_arrivee" class="form-control" value="<?= htmlspecialchars($trajet->lieu_arrivee) ?>" required>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="date_depart">Date de départ *</label>
                    <input type="date" id="date_depart" name="date_depart" class="form-control" value="<?= $trajet->date_depart ?>" required>
                </div>
                <div class="form-group">
                    <label for="heure_depart">Heure de départ *</label>
                    <input type="time" id="heure_depart" name="heure_depart" class="form-control" value="<?= substr($trajet->heure_depart, 0, 5) ?>" required>
                </div>
            </div>

             <div class="form-group-row">
                <div class="form-group">
                    <label for="date_arrivee">Date d'arrivée (optionnel)</label>
                    <input type="date" id="date_arrivee" name="date_arrivee" class="form-control" value="<?= $trajet->date_arrivee ?>">
                </div>
                <div class="form-group">
                    <label for="heure_arrivee">Heure d'arrivée (estimation) *</label>
                    <input type="time" id="heure_arrivee" name="heure_arrivee" class="form-control" value="<?= substr($trajet->heure_arrivee, 0, 5) ?>" required>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="prix_personne">Prix par personne (€) *</label>
                    <input type="number" id="prix_personne" name="prix_personne" class="form-control" value="<?= intval($trajet->prix_personne) ?>" min="0" step="1" required>
                </div>
                <div class="form-group">
                    <label for="nb_place">Places disponibles *</label>
                    <input type="number" id="nb_place" name="nb_place" class="form-control" value="<?= $trajet->nb_place ?>" min="1" max="9" required>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="voiture_id">Véhicule *</label>
                    <select id="voiture_id" name="voiture_id" class="form-control" required>
                        <option value="">Sélectionnez un véhicule</option>
                        <?php foreach ($voitures as $voiture): ?>
                            <option value="<?= $voiture->voiture_id ?>" <?= $voiture->voiture_id == $trajet->voiture_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($voiture->marque . ' ' . $voiture->modele . ' (' . $voiture->immatriculation . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statut_covoiturage_id">Statut du voyage</label>
                    <select id="statut_covoiturage_id" name="statut_covoiturage_id" class="form-control">
                        <?php foreach ($statuts as $statut): ?>
                            <option value="<?= $statut->statut_covoiturage_id ?>" <?= (isset($trajet->statut) && $trajet->statut == $statut->libelle) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($statut->libelle) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Supprimer</button>
                <a href="index.php?p=utilisateurs.profile.index" class="btn btn-secondary">Annuler</a>
            </div>
        </form>

        <form id="delete-form" action="index.php?p=trajets.delete" method="POST" style="display: none;">
            <input type="hidden" name="covoiturage_id" value="<?= $trajet->covoiturage_id ?>">
        </form>
    </div>
</section>

<script>
function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce trajet ? Cette action est irréversible.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
