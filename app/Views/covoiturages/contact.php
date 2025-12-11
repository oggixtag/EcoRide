<?php
// Vue de la page Contact - views/contact/index.php
?>

<div class="contact-container">
    <h1 class="page-title">Contactez l'équipe EcoRide</h1>

    <section class="contact-info">
        <p>
            Vous avez une question, une suggestion ou besoin d'aide concernant un covoiturage ?
            Notre équipe est là pour vous assister.
        </p>
        <div class="contact-details">
            <p><strong>Email :</strong> <a href="mailto:support@ecoride.fr">support@ecoride.fr</a></p>
            <p><strong>Téléphone :</strong> +33 (0)1 23 45 67 89</p>
            <p><strong>Adresse :</strong> 123 Rue de la Mobilité, 75000 Paris, France</p>
        </div>
    </section>

    <section class="contact-form-section">
        <h2>Envoyez-nous un message</h2>

        <!-- Le formulaire d'envoi -->
        <form action="/contact/send" method="POST" class="contact-form">

            <div class="form-group">
                <label for="name">Votre Nom Complet</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Votre Adresse Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="subject">Sujet</label>
                <select id="subject" name="subject" required>
                    <option value="" disabled selected>Choisir un sujet</option>
                    <option value="reservation">Question sur une réservation</option>
                    <option value="paiement">Problème de paiement</option>
                    <option value="suggestion">Suggestion / Amélioration</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Votre Message</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <div class="form-action">
                <button type="submit" class="submit-button">Envoyer le Message</button>
            </div>
        </form>

    </section>
</div>