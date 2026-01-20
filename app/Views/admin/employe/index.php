<section class="presentation-section">
    <div class="presentation-content">
        <h1>Admin pour les employes</h1>

        <h3>Add employe</h3>
        <a href="?p=admin.employes.add">Add employe</a>
        <br>

        <h3>List of employes</h3>

        <table class="table">
            <thead>
                <tr>
                    <td>id</td>
                    <td>nome</td>
                    <td>prenom</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employes as $employe): ?>
                    <tr>
                        <td><?= $employe->id ?></td>
                        <td><?= $employe->title ?></td>
                        <td>
                            <a class="btn-primary" href="?p=admin.employes.edit&id=<?= $employe->id ?>">Editer</a>

                            <form action="?p=admin.employes.delete" method="employe" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $employe->id ?>">
                                <button type="submit" class="btn-danger" href="?p=admin.employes.delete&id=<?= $employe->id ?>">Remove</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>