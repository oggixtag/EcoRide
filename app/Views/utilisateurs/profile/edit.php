<section class="presentation-section">
    <div class="presentation-content">
        <div class="dashboard-header header-plain">
            <h1>Modifier mon Profil</h1>
        </div>
    </div>
</section>

<?php if (!empty($message)): ?>
    <section class="presentation-section container-alert-full">
        <div class="alert alert-<?= $message_type ?> w-100">
            <?= $message ?>
        </div>
    </section>
<?php endif; ?>

<section class="presentation-section">
    <div class="presentation-content">
        <form action="index.php?p=utilisateurs.profile.edit" method="POST" class="form-utilisateurs form-centered-800">
            
            <h3>Informations Personnelles</h3>
            <div class="form-group-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateur->nom) ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateur->prenom) ?>" required>
                </div>
            </div>

            <div class="form-group-row">
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" class="form-control" value="<?= htmlspecialchars($utilisateur->telephone) ?>">
                </div>
                <div class="form-group">
                    <label for="date_naissance">Date de Naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" class="form-control" value="<?= htmlspecialchars($utilisateur->date_naissance) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-control" value="<?= htmlspecialchars($utilisateur->adresse) ?>">
            </div>

            <h3>Mon Rôle</h3>
            <?php 
                $role_id = $utilisateur->role_id;
                // Logic for checking boxes
                $check_passager = ($role_id == 2 || $role_id == 3);
                $check_chauffeur = ($role_id == 1 || $role_id == 3 || (isset($has_cars) && $has_cars));
            ?>
            <div class="form-group checkbox-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="role_passager" name="role_passager" value="2" <?= $check_passager ? 'checked' : '' ?>>
                    <label for="role_passager">Passager</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="role_chauffeur" name="role_chauffeur" value="1" <?= $check_chauffeur ? 'checked' : '' ?> onchange="toggleChauffeurInfo(this)">
                    <label for="role_chauffeur">Chauffeur</label>
                </div>
            </div>

            <div id="chauffeur-info" class="chauffeur-info-box" style="display: <?= !$check_chauffeur ? 'block' : 'none' ?>;">
                <p><strong>Information Chauffeur :</strong></p>
                <p>Pour être chauffeur il faut impérativement ajouter un véhicule dans la section <a href="index.php?p=utilisateurs.voitures.index">Mes Voitures</a>.</p>
            </div>

            <h3>Préférences</h3>
            <div class="form-group checkbox-group">
                <div class="checkbox-item">
                    <input type="checkbox" id="pref_fumeur" name="pref_fumeur" value="1" <?= in_array('Fumeur', $user_prefs) ? 'checked' : '' ?>>
                    <label for="pref_fumeur">Je suis fumeur</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="pref_animaux" name="pref_animaux" value="1" <?= in_array('Accepte les animaux', $user_prefs) ? 'checked' : '' ?>>
                    <label for="pref_animaux">J'accepte les animaux</label>
                </div>
            </div>

            <div class="form-group">
                <label for="custom_prefs">Autres préférences (séparées par des virgules)</label>
                <?php 
                    $custom_values = array_diff($user_prefs, ['Fumeur', 'Non Fumeur', 'Accepte les animaux', 'Pas d\'animaux']);
                    $custom_string = implode(', ', $custom_values);
                ?>
                <input type="text" id="custom_prefs" name="custom_prefs" class="form-control" value="<?= htmlspecialchars($custom_string) ?>" placeholder="Ex: Musique classique, Pas de discussions politiques...">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="index.php?p=utilisateurs.profile.index" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
</section>

<script>
function toggleChauffeurInfo(checkbox) {
    var infoDiv = document.getElementById('chauffeur-info');
    if (checkbox.checked) {
        infoDiv.style.display = 'none';
    } else {
        infoDiv.style.display = 'block';
    }
}
</script>