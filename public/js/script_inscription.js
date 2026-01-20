document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const usernameError = document.getElementById('usernameError');
    const emailError = document.getElementById('emailError');

    // Fonction générique pour vérifier l'unicité via AJAX
    function checkUniqueness(field, value, errorElement) {
        if (!value) {
            errorElement.textContent = '';
            return;
        }

        fetch(`index.php?p=utilisateurs.verificationUnique&field=${field}&value=${encodeURIComponent(value)}`)
            .then(response => response.json())
            .then(data => {
                if (!data.unique) {
                    errorElement.textContent = `Ce ${field === 'pseudo' ? 'pseudo' : 'email'} est déjà utilisé.`;
                    errorElement.style.color = 'red';
                } else {
                    errorElement.textContent = '';
                }
            })
            .catch(err => {
                console.error('Erreur lors de la vérification:', err);
            });
    }

    // Écouteurs d'événements blur (quand le champ perd le focus)
    if (usernameInput) {
        usernameInput.addEventListener('blur', function() {
            checkUniqueness('pseudo', this.value, usernameError);
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            checkUniqueness('email', this.value, emailError);
        });
    }
});
