<?php
// config/database.php
require_once __DIR__ . '/Env.php';

try {
    Env::load();
    
    $config = [
        'host' => Env::required('DB_HOST'),
        'port' => Env::get('DB_PORT', 3306),
        'database' => Env::required('DB_NAME'),
        'username' => Env::required('DB_USER'),
        'password' => Env::required('DB_PASS'),
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    ];
    
    // Créer la connexion PDO
    $dsn = sprintf(
        "mysql:host=%s;port=%d;dbname=%s;charset=%s",
        $config['host'],
        $config['port'],
        $config['database'],
        $config['charset']
    );
    
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    return $pdo;
    
} catch (Exception $e) {
    error_log("❌ Erreur DB: " . $e->getMessage());
    
    if (Env::get('APP_ENV') === 'production') {
        die("Erreur de connexion à la base de données. Contactez l'administrateur.");
    } else {
        die("Erreur DB: " . $e->getMessage());
    }
}