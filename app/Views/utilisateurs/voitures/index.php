<section class="presentation-section">
    <div class="presentation-content">
        <div class="dashboard-header" style="background: none; color: inherit; padding: 0; box-shadow: none; display: flex; align-items: center; justify-content: space-between; gap: 30px;">
            <h1 style="margin: 0;">Mes Voitures</h1>
            <div style="display: flex; gap: 10px;">
                <a href="index.php?p=utilisateurs.voitures.add" class="btn btn-primary" style="margin-top: 0;">Ajouter une voiture</a>
                <a href="index.php?p=utilisateurs.index" class="btn btn-secondary">Retour au profil</a>
            </div>
        </div>
    </div>
</section>

<?php if (isset($error) && $error == 'constraint'): ?>
    <section class="presentation-section" style="padding: 15px; margin: 10px auto;">
        <div class="alert alert-error" style="width: 100%; margin: 0;">
            Impossible de supprimer cette voiture car elle est associée à des trajets de covoiturage existants.
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($voitures)): ?>
    <section class="presentation-section" style="margin-top: 20px;">
        <div class="presentation-content">
            <div class="voitures-list" style="text-align: left;">
                <?php foreach ($voitures as $voiture): ?>
                    <div class="voiture-item">
                        <h3><?= htmlspecialchars($voiture->marque) ?> - <?= htmlspecialchars($voiture->modele) ?></h3>
                        <p><strong>Immatriculation:</strong> <?= htmlspecialchars($voiture->immatriculation) ?></p>
                        <p><strong>Energie:</strong> <?= htmlspecialchars($voiture->energie) ?></p>
                        <p><strong>Couleur:</strong> <?= htmlspecialchars($voiture->couleur) ?></p>
                        <div class="voiture-actions">
                            <a href="index.php?p=utilisateurs.voitures.edit&id=<?= $voiture->voiture_id ?>" class="btn btn-sm btn-secondary">Modifier</a>
                            <form action="index.php?p=utilisateurs.voitures.delete" method="POST" class="delete-car-form" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $voiture->voiture_id ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="presentation-section" style="margin-top: 20px;">
        <div class="presentation-content">
            <p class="empty-message">Aucune voiture enregistrée.</p>
        </div>
    </section>
<?php endif; ?>