<?php
// includes/config.php (version temporaire pour debug)
if (!defined('DB_HOST')) { 
    // Permettre l'accès pour les tests
    define('DB_HOST', 'winpask2025.mysql.db');
    define('DB_NAME', 'winpask2025');
    define('DB_USER', 'winpask2025');
    define('DB_PASS', '7777Jump7777');
}

// Configuration du site
define('SITE_URL', 'https://winpass.tn');
define('SITE_NAME', 'WinPass Advanced');
define('SECRET_KEY', 'votre_clé_secrète_très_longue_et_aléatoire');

// Configuration email
define('MAIL_HOST', 'ssl0.ovh.net');
define('MAIL_USER', 'contact@winpass.tn');
define('MAIL_PASS', 'votre_mot_de_passe_email');
define('MAIL_PORT', 587);
define('MAIL_FROM', 'noreply@winpass.tn');
define('MAIL_FROM_NAME', 'WinPass');
?>