<div style="margin-bottom: 20px; overflow: auto;">
    <h1 style="float: left; margin: 0;">Gestion des Utilisateurs</h1>
    <div style="float: right;">
        <a href="index.php?p=admin.dashboard" class="btn btn-secondary">Retour Tableau de Bord</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($utilisateurs as $utilisateur): ?>
            <tr>
                <td><?= $utilisateur->pseudo; ?></td>
                <td><?= $utilisateur->email; ?></td>
                <td><?= ucfirst($utilisateur->role_libelle ?? 'Inconnu'); ?></td>
                <td>
                    <?php if ($utilisateur->est_suspendu): ?>
                        <form action="index.php?p=admin.reactiverUtilisateur" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $utilisateur->utilisateur_id; ?>">
                            <button type="submit" class="btn btn-warning">Réactiver</button>
                        </form>
                    <?php else: ?>
                        <form action="index.php?p=admin.suspendreUtilisateur" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $utilisateur->utilisateur_id; ?>">
                            <button type="submit" class="btn btn-danger">Suspendre</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>