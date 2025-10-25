<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\AnnonceModel;
use Core\Session;

class HomeController
{
    private AnnonceModel $annonceModel;

    public function __construct(AnnonceModel $annonceModel)
    {
        $this->annonceModel = $annonceModel;
    }

    public function index(): void
    {
        $annonces = $this->annonceModel->getFeaturedAnnonces(6);
        $this->render('home', ['annonces' => $annonces, 'pageTitle' => 'Accueil']);
    }

    public function partenaires(): void
    {
        $this->render('partenaires', ['pageTitle' => 'Partenaires']);
    }

    public function boutique(): void
    {
        $this->render('boutique', ['pageTitle' => 'Boutique']);
    }
    
    public function annonceDetail(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === 0) {
            http_response_code(404);
            $this->render('error', ['message' => 'Annonce non trouvée']);
            return;
        }
        
        $annonce = $this->annonceModel->findById($id);
        if (!$annonce) {
            http_response_code(404);
            $this->render('error', ['message' => 'Annonce non trouvée']);
            return;
        }
        
        $this->render('annonce-detail', ['annonce' => $annonce, 'pageTitle' => $annonce['titre']]);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . "/../Views/{$view}.php";
        require_once __DIR__ . '/../Views/layout/footer.php';
    }
}
