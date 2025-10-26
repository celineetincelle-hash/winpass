<?php
// config/Env.php
class Env {
    private static $vars = [];
    private static $loaded = false;
    
    public static function load($path = null) {
        if (self::$loaded) {
            return; // Éviter les chargements multiples
        }
        
        // Chemin adapté à OVH
        if ($path === null) {
            // Remonter depuis www/ vers la racine
            $path = dirname(__DIR__) . '/.env';
            
            // Si pas trouvé, essayer un niveau au-dessus
            if (!file_exists($path)) {
                $path = dirname(dirname(__DIR__)) . '/.env';
            }
        }
        
        if (!file_exists($path)) {
            throw new Exception("❌ Fichier .env introuvable à: $path");
        }
        
        // Vérifier les permissions (lecture seule recommandé)
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        if ($perms !== '0400' && $perms !== '0600') {
            error_log("⚠️ SÉCURITÉ: .env devrait avoir les permissions 400 ou 600 (actuellement: $perms)");
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignorer commentaires
            if (strpos($line, '#') === 0) {
                continue;
            }
            
            // Parser la ligne
            if (strpos($line, '=') === false) {
                continue;
            }
            
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Retirer guillemets
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            self::$vars[$key] = $value;
            putenv("$key=$value");
        }
        
        self::$loaded = true;
        
        // Valider les variables critiques
        self::validate();
    }
    
    public static function get($key, $default = null) {
        if (!self::$loaded) {
            self::load();
        }
        return self::$vars[$key] ?? getenv($key) ?: $default;
    }
    
    public static function required($key) {
        $value = self::get($key);
        if ($value === null || $value === '') {
            throw new Exception("❌ Variable d'environnement requise manquante: $key");
        }
        return $value;
    }
    
    private static function validate() {
        $required = [
            'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS',
            'SECRET_KEY', 'APP_URL'
        ];
        
        foreach ($required as $key) {
            if (empty(self::get($key))) {
                throw new Exception("❌ Variable requise manquante ou vide: $key");
            }
        }
        
        // Vérifier que les clés ne sont pas par défaut
        if (self::get('SECRET_KEY') === 'change_me_secret_key') {
            throw new Exception("❌ SÉCURITÉ: SECRET_KEY utilise encore la valeur par défaut!");
        }
        
        // En production, vérifier HTTPS
        if (self::get('APP_ENV') === 'production') {
            $url = self::get('APP_URL');
            if (strpos($url, 'https://') !== 0) {
                error_log("⚠️ APP_URL devrait utiliser HTTPS en production");
            }
        }
    }
}