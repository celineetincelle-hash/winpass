<?php
// src/Controllers/AuthController.php
require_once __DIR__ . '/../Middleware/RateLimiter.php';

class AuthController {
    private $rateLimiter;
    
    public function __construct() {
        // 5 tentatives par 15 minutes
        $this->rateLimiter = new RateLimiter(5, 15);
    }
    
    public function login() {
        // Vérifier le rate limit AVANT tout traitement
        $this->rateLimiter->check('login');
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validation basique
        if (empty($email) || empty($password)) {
            $this->rateLimiter->hit('login');
            return $this->jsonError('Email et mot de passe requis', 400);
        }
        
        // Tentative de connexion
        $user = $this->authenticateUser($email, $password);
        
        if (!$user) {
            // Incrémenter le compteur d'échecs
            $attempts = $this->rateLimiter->hit('login');
            $remaining = $this->rateLimiter->remaining('login');
            
            // Logger la tentative échouée
            error_log("❌ Login échoué pour: $email (IP: " . $_SERVER['REMOTE_ADDR'] . ")");
            
            return $this->jsonError(
                'Identifiants invalides',
                401,
                [
                    'attempts_remaining' => $remaining,
                    'locked_until' => $remaining === 0 ? $this->rateLimiter->availableAt('login') : null
                ]
            );
        }
        
        // Connexion réussie - Réinitialiser le compteur
        $this->rateLimiter->clear('login');
        
        // Régénérer l'ID de session (sécurité)
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        
        // Logger la connexion réussie
        error_log("✅ Login réussi pour: $email");
        
        return $this->jsonSuccess([
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role']
            ],
            'redirect' => $user['role'] === 'admin' ? '/admin/dashboard' : '/dashboard'
        ]);
    }
    
    private function authenticateUser($email, $password) {
        // Votre logique d'authentification
        // ...
    }
    
    private function jsonSuccess($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }
    
    private function jsonError($message, $code = 400, $extra = []) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(array_merge(['error' => $message], $extra));
        exit;
    }
}