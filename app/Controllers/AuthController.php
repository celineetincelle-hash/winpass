<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use Core\Database;
use Core\Session;
use Core\CSRF;

class AuthController
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function showLogin(): void
    {
        $this->render('login', ['pageTitle' => 'Connexion']);
    }

    public function showRegister(): void
    {
        $this->render('register', ['pageTitle' => 'Inscription']);
    }

    public function login(): void
    {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!CSRF::validateToken($data['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Erreur CSRF.']);
            return;
        }

        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis.']);
            return;
        }
        
        $user = $this->userModel->verifyPassword($email, $password);
        if ($user) {
            Session::start(true); // Régénérer l'ID de session pour la sécurité
            Session::set('user_id', $user['id']);
            Session::set('user_type', $user['type']);
            Session::set('user_name', $user['prenom'] . ' ' . $user['nom']);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Connexion réussie.', 
                'user_type' => $user['type']
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Identifiants incorrects.']);
        }
    }

    public function register(): void
    {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);

        if (!CSRF::validateToken($data['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Erreur CSRF.']);
            return;
        }
        
        // Validation des données
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $password = $data['password'] ?? '';
        $type = in_array($data['type'] ?? '', ['user', 'partner']) ? $data['type'] : 'user';
        
        if (empty($email) || empty($nom) || empty($prenom) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email invalide.']);
            return;
        }
        
        if (strlen($password) < 8) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères.']);
            return;
        }
        
        if ($this->userModel->findByEmail($email)) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
            return;
        }

        $userData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'type' => $type
        ];
        
        if ($type === 'partner' && isset($data['partenaire'])) {
            $userData['partenaire'] = $data['partenaire'];
        }

        if ($this->userModel->create($userData)) {
            echo json_encode(['success' => true, 'message' => 'Inscription réussie. Vous pouvez vous connecter.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l'inscription.']);
        }
    }
    
    public function logout(): void
    {
        Session::destroy();
        header('Location: /');
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . "/../Views/{$view}.php";
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
