document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const pseudoInput = document.getElementById('pseudo');
    const emailInput = document.getElementById('email');
    const emailConfirmationInput = document.getElementById('emailConfirmation');
    const passwordInput = document.getElementById('password');

    const pseudoError = document.getElementById('pseudoError');
    const emailError = document.getElementById('emailError');
    const emailErrorConfirmation = document.getElementById('emailErrorConfirmation');
    const passwordError = document.getElementById('passwordError');

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

    // Fonctions de validation
    function validateEmail(email) {
        const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return regex.test(email);
    }

    function validatePassword(password) {
        // Au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        return regex.test(password);
    }

    function checkEmail() {
        // Suppression des espaces
        emailInput.value = emailInput.value.trim();
        const value = emailInput.value;
        
        if (!value) {
           emailError.textContent = '';
           return false;
        }

        if (!validateEmail(value)) {
            emailError.textContent = "L'adresse email n'est pas valide (format attendu : nom@domaine.extension).";
            emailError.style.color = 'red';
            return false;
        } else {
            emailError.textContent = '';
            // Si le format est bon, on vérifie l'unicité
            checkUniqueness('email', value, emailError);
            return true;
        }
    }

    function checkEmailConfirmation() {
        emailConfirmationInput.value = emailConfirmationInput.value.trim();
        const email = emailInput.value;
        const confirmation = emailConfirmationInput.value;

        if (email !== confirmation) {
            emailErrorConfirmation.textContent = "Les adresses email ne correspondent pas.";
            emailErrorConfirmation.style.color = 'red';
            return false;
        } else {
            emailErrorConfirmation.textContent = '';
            return true;
        }
    }

    function checkPassword() {
        const value = passwordInput.value;
        if (!validatePassword(value)) {
            passwordError.textContent = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
            passwordError.style.color = 'red';
            return false;
        } else {
            passwordError.textContent = '';
            return true;
        }
    }

    // Écouteurs d'événements
    if (pseudoInput) {
        pseudoInput.addEventListener('blur', function() {
            checkUniqueness('pseudo', this.value, pseudoError);
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', checkEmail);
        emailInput.addEventListener('input', function() {
             // Nettoyer l'erreur de format pendant la frappe si l'utilisateur corrige, 
             // mais on ne valide complètement qu'au blur pour ne pas être agaçant
             if(emailError.textContent.includes("valide")) emailError.textContent = '';
        });
    }

    if (emailConfirmationInput) {
        // Vérifier la correspondance quand on quitte le champ de confirmation
        emailConfirmationInput.addEventListener('blur', checkEmailConfirmation);
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', checkPassword);
    }

    // Interception de la soumission du formulaire
    if (form) {
        form.addEventListener('submit', function(e) {
            let valid = true;

            // On relance toutes les vérifications
            if (!checkEmail()) valid = false;
            
            // On vérifie la confirmation seulement si l'email est rempli
            if (emailInput.value && !checkEmailConfirmation()) valid = false;
            
            if (!checkPassword()) valid = false;
            
            // Si le pseudo est vide (géré par required, mais au cas où)
            if (!pseudoInput.value.trim()) valid = false;

            // Vérification des erreurs d'unicité affichées (si le serveur a répondu "déjà utilisé")
            if (pseudoError.textContent !== '' || emailError.textContent !== '') {
                valid = false;
            }

            if (!valid) {
                e.preventDefault(); // Empêcher l'envoi
                alert("Veuillez corriger les erreurs dans le formulaire avant de soumettre.");
            }
        });
    }
});
