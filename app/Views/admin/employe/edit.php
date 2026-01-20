<section class="presentation-section">
    <div class="presentation-content">
        <?php
        // $form->input('content', 'Contenu de l\'article', ['type' => 'textarea']);
        ?>

        <form action="" method="post">
            <?= $form->input('nom', 'Nom de l\'employe'); ?>
            <?= $form->select('id_dept', 'Departement', $departements); ?>
            <?= $form->select('id_poste', 'Poste', $postes); ?>
            <?= $form->select('id_emp', 'Poste', $employes); ?>
            <button class="btn btn-primary">Sauvegarder</button>
        </form>

        <a href="?p=admin.employes.index">go to employes index</a>
    </div>
</section>