<section class="presentation-section">
    <div class="presentation-content">
        <div class="login-box">
            <h1>Inscription</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= htmlspecialchars($message_type) ?>">
                    <p><?= htmlspecialchars($message) ?></p>
                </div>
            <?php endif; ?>

            <form id="registerForm" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Pseudo</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Choisissez un pseudo"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        required />
                    <span id="usernameError" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="email">Adresse mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Votre adresse email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required />
                    <span id="emailError" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Choisissez un mot de passe sécurisé"
                        required />
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">S'inscrir</button>
                    <a href="index.php?p=utilisateurs.inscrir" class="btn btn-secondary" style="text-decoration:none; display:inline-block; text-align:center; line-height: normal;">Effacé</a>
                </div>

                <div class="auth-links">
                    <p>Tu as déjà un compte ? <a href="index.php?p=utilisateurs.login">Se connecter</a></p>
                </div>
            </form>
        </div>
    </div>
</section>

<script src="/EcoRide/public/js/script_inscription.js"></script>