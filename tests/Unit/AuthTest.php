<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    /**
     * Teste que le hachage de mot de passe fonctionne correctement.
     */
    public function testPasswordHash()
    {
        $password = 'Secret123!';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('WrongPassword', $hash));
    }

    /**
     * Teste la validation des données d'inscription (existant dans UtilisateursController)
     */
    public function testRegistrationValidation()
    {
        // On teste la méthode statique validateRegistrationData
        require_once __DIR__ . '/../../app/Controller/UtilisateursController.php';

        // Cas valide
        $errors = \NsAppEcoride\Controller\UtilisateursController::validateRegistrationData(
            'test@example.com',
            'test@example.com',
            'StrongPass123!'
        );
        $this->assertEmpty($errors);

        // Cas invalide (emails différents)
        $errors = \NsAppEcoride\Controller\UtilisateursController::validateRegistrationData(
            'test@example.com',
            'other@example.com',
            'StrongPass123!'
        );
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('correspondent pas', $errors[0]);

        // Cas invalide (mot de passe faible)
        $errors = \NsAppEcoride\Controller\UtilisateursController::validateRegistrationData(
            'test@example.com',
            'test@example.com',
            'weak'
        );
        $this->assertNotEmpty($errors);
    }
}
