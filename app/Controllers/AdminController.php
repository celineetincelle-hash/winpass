<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnnonceModel;
use Core\Session;

class AdminController
{
    private UserModel $userModel;
    private AnnonceModel $annonceModel;

    public function __construct(UserModel $userModel, AnnonceModel $annonceModel)
    {
        $this->userModel = $userModel;
        $this->annonceModel = $annonceModel;
    }

    public function dashboard(): void
    {
        Session::start();
        if (!Session::has('user_id') || Session::get('user_type') !== 'admin') {
            http_response_code(403);
            $this->render('error', ['message' => 'Accès non autorisé']);
            return;
        }
        
        $stats = $this->getStats();
        $this->render('admin/dashboard', ['stats' => $stats, 'pageTitle' => 'Dashboard Admin']);
    }

    public function users(): void
    {
        Session::start();
        if (!Session::has('user_id') || Session::get('user_type') !== 'admin') { 
            http_response_code(403);
            $this->render('error', ['message' => 'Accès non autorisé']);
            return;
        }
        
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $usersData = $this->userModel->getAllUsers($page);
        $this->render('admin/users', $usersData + ['pageTitle' => 'Gestion des Utilisateurs']);
    }
    
    public function updateUserStatus(): void
    {
        header('Content-Type: application/json');
        Session::start();
        
        if (!Session::has('user_id') || Session::get('user_type') !== 'admin') { 
            http_response_code(403);
            echo json_encode(['success' => false]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = filter_var($data['id'] ?? 0, FILTER_VALIDATE_INT);
        $status = in_array($data['status'] ?? '', ['actif', 'inactif']) ? $data['status'] : null;
        
        if ($id > 0 && $status) {
            if ($this->userModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

    private function getStats(): array
    {
        try {
            $db = $this->userModel->db ?? new Core\Database();
            
            return [
                'totalUsers' => $db->query("SELECT COUNT(*) as count FROM utilisateurs")->fetchColumn(),
                'totalPartners' => $db->query("SELECT COUNT(*) as count FROM utilisateurs WHERE type='partner'")->fetchColumn(),
                'totalAnnonces' => $db->query("SELECT COUNT(*) as count FROM annonces")->fetchColumn(),
                'activeAnnonces' => $db->query("SELECT COUNT(*) as count FROM annonces WHERE statut='actif'")->fetchColumn(),
            ];
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            return [
                'totalUsers' => 0,
                'totalPartners' => 0,
                'totalAnnonces' => 0,
                'activeAnnonces' => 0,
            ];
        }
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . "/../Views/{$view}.php";
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
