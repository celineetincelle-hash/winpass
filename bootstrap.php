<?php
/**
 * bootstrap.php
 * Point d'entrée central pour l'initialisation de WinPass Tunisia.
 */

// Empêche l'accès direct si le fichier est appelé hors contexte web
if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit('Accès direct interdit.');
}

// Définit la racine du projet
define('ROOT', __DIR__);

// Autoloader PSR-4 manuel pour le namespace Core
spl_autoload_register(function (string $class): void {
    // Seulement pour les classes dans le namespace Core\
    if (str_starts_with($class, 'Core\\')) {
        $relativeClass = substr($class, 5); // retire "Core\"
        $file = ROOT . '/core/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Démarrage sécurisé de la session via la classe dédiée
\Core\Session::start();