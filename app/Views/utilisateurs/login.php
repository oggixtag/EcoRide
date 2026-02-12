<section class="presentation-section">
    <div class="presentation-content">
        <div class="login-box">
            <h1>Connexion</h1>
            <?php if ($errors): ?>
                <div class="alert alert-error">
                    <p>Identifiants invalides. Veuillez réessayer.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($message) && isset($message_type)): ?>
                <div class="alert alert-<?= htmlspecialchars($message_type) ?>">
                    <p><?= htmlspecialchars($message) ?></p>
                </div>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form id="loginForm" method="POST" class="login-form" style="display: block;">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input
                        type="text"
                        id="pseudo"
                        name="pseudo"
                        class="form-control"
                        placeholder="Entrez votre pseudo"
                        required />
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Entrez votre mot de passe"
                        required />
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                    <button type="button" id="toggleRecoveryBtn" class="btn btn-secondary" onclick="toggleForms(); return false;">Mot de passe oublié ?</button>
                </div>
                
                <div class="auth-links" style="margin-top: 15px; text-align: center;">
                    <a href="index.php?p=utilisateurs.inscrir">Inscris-toi avec ton adresse e-mail</a>
                </div>
            </form>

            <!-- Formulaire de récupération de mot de passe -->
            <form id="recoveryForm" method="POST" action="index.php?p=utilisateurs.recupererPassword" class="login-form" style="display: none;">
                <div class="form-group">
                    <label for="recoveryMethod">Chercher par :</label>
                    <div class="recovery-method-selector">
                        <label>
                            <input type="radio" name="recovery_method" value="email" checked> Email
                        </label>
                        <label>
                            <input type="radio" name="recovery_method" value="pseudo"> Pseudo
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="recoveryInput">Email ou Pseudo</label>
                    <input
                        type="text"
                        id="recoveryInput"
                        name="recovery_input"
                        class="form-control"
                        placeholder="Entrez votre email"
                        required />
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Récupérer mon mot de passe</button>
                    <button type="button" id="backToLoginBtn" class="btn btn-secondary" onclick="toggleForms(); return false;">Retour à la connexion</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function toggleForms() {
        const loginForm = document.getElementById('loginForm');
        const recoveryForm = document.getElementById('recoveryForm');
        const loginTitle = document.getElementById('loginTitle');

        // Basculer l'affichage
        if (loginForm.style.display === 'none') {
            loginForm.style.display = 'block';
            recoveryForm.style.display = 'none';
            loginTitle.textContent = 'Connexion';
        } else {
            loginForm.style.display = 'none';
            recoveryForm.style.display = 'block';
            loginTitle.textContent = 'Récupérer mon mot de passe';
        }
    }

    // Mettre à jour le placeholder en fonction de la méthode de récupération sélectionnée
    document.addEventListener('DOMContentLoaded', function() {
        const recoveryInputs = document.querySelectorAll('input[name="recovery_method"]');
        const recoveryInput = document.getElementById('recoveryInput');

        recoveryInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'email') {
                    recoveryInput.placeholder = 'Entrez votre email';
                    recoveryInput.type = 'email';
                } else {
                    recoveryInput.placeholder = 'Entrez votre pseudo';
                    recoveryInput.type = 'text';
                }
            });
        });
    });
</script>