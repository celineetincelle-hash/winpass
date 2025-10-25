<?php
declare(strict_types=1);

namespace Core;

/**
 * Gère le démarrage de session de manière sécurisée.
 */
class Session
{
    private static bool $started = false;
    
    public static function start(bool $regenerate = false): void
    {
        if (self::$started) {
            return;
        }
        
        // Configuration sécurisée des sessions
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_secure', '1');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.gc_maxlifetime', '7200'); // 2 heures
        ini_set('session.use_strict_mode', '1');
        
        session_start();
        self::$started = true;
        
        if ($regenerate) {
            session_regenerate_id(true);
        }
    }
    
    public static function destroy(): void
    {
        if (self::$started) {
            session_destroy();
            self::$started = false;
        }
    }
    
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function flash(string $key, $value = null)
    {
        self::start();
        
        if ($value === null) {
            $flash = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $flash;
        } else {
            $_SESSION['flash'][$key] = $value;
        }
    }
}
