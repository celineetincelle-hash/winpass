<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../core/Database.php';

try {
    $db = new Core\Database();
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
    $itemsPerPage = 12;
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
    $city = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_STRING);

    $offset = ($page - 1) * $itemsPerPage;

    $sql = "SELECT a.*, p.nom_entreprise, p.ville, p.latitude, p.longitude, c.nom AS categorie_nom, c.couleur AS categorie_couleur
            FROM annonces a
            JOIN partenaires p ON a.partenaire_id = p.id
            JOIN categories c ON a.categorie_id = c.id
            WHERE a.statut = 'actif' AND p.statut = 'actif'";
    $params = [];

    if ($search) {
        $sql .= " AND (a.titre LIKE :search OR a.description LIKE :search)";
        $params['search'] = "%$search%";
    }
    if ($category) {
        $sql .= " AND a.categorie_id = :category";
        $params['category'] = $category;
    }
    if ($city) {
        $sql .= " AND p.ville = :city";
        $params['city'] = $city;
    }

    $sql .= " ORDER BY a.date_creation DESC LIMIT :limit OFFSET :offset";
    $params['limit'] = $itemsPerPage;
    $params['offset'] = $offset;

    $annonces = $db->query($sql, $params)->fetchAll();

    $countSql = "SELECT COUNT(*) as total FROM annonces a WHERE a.statut = 'actif'";
    $totalItems = (int)$db->query($countSql)->fetchColumn();
    $totalPages = (int)ceil($totalItems / $itemsPerPage);

    echo json_encode([
        'success' => true, 
        'data' => [
            'annonces' => $annonces, 
            'pagination' => [
                'currentPage' => $page, 
                'totalPages' => $totalPages, 
                'totalItems' => $totalItems
            ]
        ]
    ]);
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>
