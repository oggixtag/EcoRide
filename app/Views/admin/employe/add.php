<section class="presentation-section">
    <div class="presentation-content">
        <form action="" method="post">
            <?= $form->input('title', 'Titre de l\'article'); ?>
            <?= $form->input('content', 'Contenu de l\'article', ['type' => 'textarea']); ?>
            <?= $form->select('category_id', 'Catégorie', $categories); ?>
            <button class="btn btn-primary">Sauvegarder</button>
        </form>

        <a href="?p=admin.employes.index">go to employes index</a>
    </div>
</section>