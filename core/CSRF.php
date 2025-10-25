<?php
declare(strict_types=1);

namespace Core;

/**
 * Gère la génération et la validation des jetons CSRF.
 */
class CSRF
{
    private const TOKEN_LENGTH = 32;
    private const SESSION_KEY = 'csrf_token';
    
    public static function generateToken(): string
    {
        \Core\Session::start();
        
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(self::TOKEN_LENGTH));
        }
        
        return $_SESSION[self::SESSION_KEY];
    }

    public static function validateToken(string $token): bool
    {
        \Core\Session::start();
        
        if (empty($_SESSION[self::SESSION_KEY]) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }
    
    public static function refreshToken(): string
    {
        \Core\Session::start();
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(self::TOKEN_LENGTH));
        return $_SESSION[self::SESSION_KEY];
    }
    
    public static function getHiddenInput(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::generateToken() . '">';
    }
}
