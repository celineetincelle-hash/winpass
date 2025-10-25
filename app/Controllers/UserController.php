<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CarteModel;
use Core\Session;
use Core\CSRF;

class UserController
{
    private UserModel $userModel;
    private CarteModel $carteModel;

    public function __construct(UserModel $userModel, CarteModel $carteModel)
    {
        $this->userModel = $userModel;
        $this->carteModel = $carteModel;
    }

    public function profile(): void
    {
        Session::start();
        if (!Session::has('user_id')) {
            header('Location: /index.php?route=login');
            exit;
        }
        
        $user = $this->userModel->findById(Session::get('user_id'));
        $activeCard = $this->carteModel->getActiveCarteForUser(Session::get('user_id'));
        
        $this->render('profil', ['user' => $user, 'activeCard' => $activeCard, 'pageTitle' => 'Mon Profil']);
    }

    public function updateProfile(): void
    {
        header('Content-Type: application/json');
        Session::start();
        
        if (!Session::has('user_id')) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (!CSRF::validateToken($data['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Erreur CSRF.']);
            return;
        }

        // Validation des données
        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $telephone = trim($data['telephone'] ?? '');
        
        if (empty($nom) || empty($prenom)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nom et prénom requis.']);
            return;
        }

        $updateData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone ?: null
        ];

        if ($this->userModel->updateProfile(Session::get('user_id'), $updateData)) {
            // Mettre à jour les données de session
            Session::set('user_name', $prenom . ' ' . $nom);
            echo json_encode(['success' => true, 'message' => 'Profil mis à jour.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
        }
    }
    
    public function getQrHistory(): void
    {
        header('Content-Type: application/json');
        Session::start();
        
        if (!Session::has('user_id')) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
            return;
        }
        
        // Simulation de l'historique
        $history = [
            ['annonce_titre' => 'Offre Spaghetti', 'date_utilisation' => '2023-10-27 12:30:00'],
            ['annonce_titre' => 'Réduction Pizza', 'date_utilisation' => '2023-10-25 19:00:00'],
        ];
        
        echo json_encode(['success' => true, 'data' => $history]);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . "/../Views/{$view}.php";
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
