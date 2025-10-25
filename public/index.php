<?php
/**
 * Front Controller - Point d'entrée unique de l'application.
 */
use Core\Session;
use Core\Database;
use App\Controllers\AdminController;

require_once __DIR__ . '/../core/Session.php';
Session::start();

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/CSRF.php';

spl_autoload_register(function ($class) {
    $prefixes = ['App\\' => __DIR__ . '/../app/', 'Core\\' => __DIR__ . '/../core/'];
    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) continue;
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) { require $file; return; }
    }
});

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 0); // Ne pas afficher les erreurs en production
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/errors.log');

// Création du répertoire de logs s'il n'existe pas
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

// Fonction de gestion des erreurs personnalisée
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    error_log("PHP Error: {$message} in {$file} on line {$line}");
    if (defined('DEBUG') && DEBUG) {
        echo "<div class='error'>Error: {$message} in {$file} on line {$line}</div>";
    }
});

// Fonction de gestion des exceptions
set_exception_handler(function($exception) {
    error_log("Uncaught exception: " . $exception->getMessage());
    if (defined('DEBUG') && DEBUG) {
        echo "<div class='error'>Uncaught exception: " . $exception->getMessage() . "</div>";
    } else {
        http_response_code(500);
        include __DIR__ . '/../app/Views/error.php';
    }
});

// Routage
 $route = $_GET['route'] ?? 'home';
 $subpage = $_GET['subpage'] ?? null;

try {
    $db = new Database();
    
    switch ($route) {
        case 'home':
            $controller = new \App\Controllers\HomeController(new \App\Models\AnnonceModel($db));
            $controller->index();
            break;
        case 'partenaires':
            $controller = new \App\Controllers\HomeController(new \App\Models\AnnonceModel($db));
            $controller->partenaires();
            break;
        case 'boutique':
            $controller = new \App\Controllers\HomeController(new \App\Models\AnnonceModel($db));
            $controller->boutique();
            break;
        case 'annonce-detail':
            $controller = new \App\Controllers\HomeController(new \App\Models\AnnonceModel($db));
            $controller->annonceDetail();
            break;
        case 'login':
        case 'register':
        case 'logout':
            $authController = new \App\Controllers\AuthController(new \App\Models\UserModel($db));
            $authController->{$route}();
            break;
        case 'profile':
            $userController = new \App\Controllers\UserController(new \App\Models\UserModel($db), new \App\Models\CarteModel($db));
            $userController->profile();
            break;
        case 'admin':
            $adminController = new \App\Controllers\AdminController(new \App\Models\UserModel($db), new \App\Models\AnnonceModel($db));
            if ($subpage) {
                $adminController->{$subpage}();
            } else {
                $adminController->dashboard();
            }
            break;
        default:
            http_response_code(404);
            include __DIR__ . '/../app/Views/error.php';
            break;
    }
} catch (Exception $e) {
    error_log("Route error: " . $e->getMessage());
    http_response_code(500);
    include __DIR__ . '/../app/Views/error.php';
}
