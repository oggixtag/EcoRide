<div class="row">
    <div class="col-md-4 offset-md-4" style="margin-top: 50px;">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title text-center">Espace Employé - Connexion</h3>
            </div>
            <div class="card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        Identifiants incorrects
                    </div>
                <?php endif; ?>

                <form method="post">
                    <?= $form->input('email', 'Email'); ?>
                    <?= $form->input('password', 'Mot de passe', ['type' => 'password']); ?>
                    <button class="btn btn-primary btn-block">Se connecter</button>
                    <!-- IMPORTANT: The previous design had btn-primary, ensure style_app.css defines it or use bootstrap -->
                </form>
            </div>
        </div>
    </div>
</div>
