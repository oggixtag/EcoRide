<?php

namespace NsAppEcoride\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    /**
     * Envoie un email
     * @param string $to Email du destinataire
     * @param string $subject Sujet de l'email
     * @param string $body Contenu HTML de l'email
     * @param string $altBody Contenu texte brut (optionnel)
     * @return bool
     */
    public function send($to, $subject, $body, $altBody = '')
    {
        // Debug Log
        file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). " - START Mailer::send($to)\n", FILE_APPEND);
        
        $mail = new PHPMailer(true);

        try {
            // Configuration serveur 
            // Note: In production these should be in a config file
            $mail->SMTPDebug = 2; 
            $mail->Debugoutput = function($str, $level) {
                file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). "\t" . $str . "\n", FILE_APPEND);
            };

            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = '463794f0298096'; 
            $mail->Password   = '885b66ca80971b';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;
            $mail->CharSet    = 'UTF-8';

            // Destinataires
            $mail->setFrom('no-reply@ecoride.fr', 'EcoRide');
            $mail->addAddress($to);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            $mail->send();
            file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). " - SUCCESS Mailer::send\n", FILE_APPEND);
            return true;
        } catch (\Throwable $e) {
            file_put_contents(ROOT . '/log/email_debug.txt', "MAIL ERROR: " . $e->getMessage() . "\n" . $mail->ErrorInfo . "\n", FILE_APPEND);
            return false;
        }
    }
}
