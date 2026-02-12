<div style="margin-bottom: 20px; overflow: auto;">
    <h1 style="float: left; margin: 0;">Ajouter un employé</h1>
    <div style="float: right;">
        <a href="index.php?p=admin.employes.index" class="btn btn-primary">Gestion Employés</a>
    </div>
</div>

<form action="" method="post">
    <?= $form->input('nom', 'Nom', ['required' => true]); ?>
    <?= $form->input('prenom', 'Prénom', ['required' => true]); ?>
    <?= $form->input('pseudo', 'Pseudo', ['required' => true]); ?>
    <?= $form->input('email', 'Email', ['type' => 'email', 'required' => true]); ?>
    <?= $form->input('password', 'Mot de passe', ['type' => 'password', 'required' => true]); ?>
    <?= $form->input('date_embauche', 'Date d\'embauche', ['type' => 'date', 'required' => true]); ?>
    <?= $form->input('salaire', 'Salaire', ['type' => 'number', 'required' => true, 'step' => '1']); ?>
    
    <?= $form->select('id_poste', 'Poste', $postes, ['required' => true]); ?>
    <?= $form->select('id_dept', 'Département', $departements, ['required' => true]); ?>
    
    <!-- Sélection du manager (Obligatoire) -->
    <?= $form->select('id_manager', 'Manager', $managers, ['required' => true]); ?>

    <button class="btn btn-primary">Sauvegarder</button>
</form>