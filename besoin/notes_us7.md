# Notes Implémentation US 7 : Création de compte

 

## Configuration Email (SMTP)
L'envoi d'email est désormais implémenté avec **PHPMailer** dans `UtilisateursController.php`.

**IMPORTANT** : Vous devez configurer vos propres paramètres SMTP dans la méthode `sendValidationEmail` du fichier `app/Controller/UtilisateursController.php` (lignes 280+).

Actuellement, le code utilise des paramètres fictifs (Mailtrap) :
```php
$mail->Host       = 'sandbox.smtp.mailtrap.io'; 
$mail->Username   = 'username_fictif'; 
$mail->Password   = 'password_fictif';
$mail->Port       = 2525;
```
Remplacez ces valeurs par celles de votre fournisseur d'email (Gmail, Mailtrap, SendGrid, ou votre FAI).

### Option 2 : mail() natif (php.ini)
Modifier `php.ini` :
```ini
[mail function]
SMTP = smtp.fai.fr
smtp_port = 25
sendmail_from = me@example.com
```
 

