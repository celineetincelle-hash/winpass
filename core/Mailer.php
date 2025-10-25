<?php
declare(strict_types=1);

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mail;
    private array $config;
    
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->config = $this->loadConfig();
        $this->configure();
    }
    
    private function loadConfig(): array
    {
        if (file_exists(__DIR__ . '/../includes/config.php')) {
            require_once __DIR__ . '/../includes/config.php';
            return [
                'host' => MAIL_HOST,
                'user' => MAIL_USER,
                'pass' => MAIL_PASS,
                'port' => MAIL_PORT,
                'from' => MAIL_FROM,
                'from_name' => MAIL_FROM_NAME
            ];
        }
        
        // Configuration par défaut pour le développement
        return [
            'host' => 'smtp.example.com',
            'user' => 'user@example.com',
            'pass' => 'password',
            'port' => 587,
            'from' => 'noreply@winpass.tn',
            'from_name' => 'WinPass'
        ];
    }
    
    private function configure(): void
    {
        $this->mail->isSMTP();
        $this->mail->Host       = $this->config['host'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->config['user'];
        $this->mail->Password   = $this->config['pass'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = $this->config['port'];
        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($this->config['from'], $this->config['from_name']);
        $this->mail->addReplyTo($this->config['from'], $this->config['from_name']);
    }

    public function sendQRCode(string $to, string $subject, string $qrCodePath): bool
    {
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = "Veuillez trouver votre QR Code WinPass en pièce jointe.";
            $this->mail->addAttachment($qrCodePath, 'WinPass-QR.png');
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }
    
    public function sendEmail(string $to, string $subject, string $body, bool $isHtml = false): bool
    {
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->isHTML($isHtml);
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
