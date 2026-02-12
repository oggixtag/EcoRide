<div style="margin-bottom: 20px; overflow: auto;">
    <h1 style="float: left; margin: 0;">Gestion des Employés</h1>
    <div style="float: right;">
        <a href="index.php?p=admin.employes.add" class="btn btn-success" style="margin-right: 10px;">Ajouter un employé</a>
        <a href="index.php?p=admin.dashboard" class="btn btn-secondary">Retour Tableau de Bord</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Pseudo</th>
            <th>Poste</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($employes as $employe): ?>
            <tr>
                <td><?= $employe->nom; ?></td>
                <td><?= $employe->prenom; ?></td>
                <td><?= $employe->email; ?></td>
                <td><?= $employe->pseudo; ?></td>
                <td><?= htmlspecialchars($employe->poste_libelle ?? 'Inconnu'); ?></td>
                <td>
                    <a href="index.php?p=admin.employes.edit&id_emp=<?= $employe->id_emp; ?>" class="btn btn-primary">Editer</a>
                    
                    <form action="index.php?p=admin.employes.delete" method="post" style="display:inline;">
                        <input type="hidden" name="id_emp" value="<?= $employe->id_emp; ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet employé ?');">Supprimer</button>
                    </form>

                    <?php if ($employe->est_suspendu): ?>
                        <form action="index.php?p=admin.employes.reactiver" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $employe->id_emp; ?>">
                            <button type="submit" class="btn btn-warning">Réactiver</button>
                        </form>
                    <?php else: ?>
                        <form action="index.php?p=admin.employes.suspendre" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $employe->id_emp; ?>">
                            <button type="submit" class="btn btn-warning">Suspendre</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
