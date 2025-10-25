<?php
declare(strict_types=1);

namespace App\Models;

use Core\Database;

class AnnonceModel
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getFeaturedAnnonces(int $limit = 6): array
    {
        $sql = "SELECT a.*, p.nom_entreprise, p.ville, p.latitude, p.longitude, c.nom AS categorie_nom, c.couleur AS categorie_couleur
                FROM annonces a
                JOIN partenaires p ON a.partenaire_id = p.id
                JOIN categories c ON a.categorie_id = c.id
                WHERE a.statut = 'actif' AND p.statut = 'actif'
                ORDER BY a.date_creation DESC
                LIMIT :limit";
        return $this->db->query($sql, ['limit' => $limit])->fetchAll();
    }

    public function getPaginatedAnnonces(int $page = 1, int $itemsPerPage = 12, ?string $search = null, ?int $category = null, ?string $city = null): array
    {
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
        
        $annonces = $this->db->query($sql, $params)->fetchAll();

        $countSql = "SELECT COUNT(*) as total FROM annonces a WHERE a.statut = 'actif'";
        $totalItems = (int)$this->db->query($countSql)->fetchColumn();
        $totalPages = (int)ceil($totalItems / $itemsPerPage);

        return [
            'annonces' => $annonces,
            'pagination' => ['currentPage' => $page, 'totalPages' => $totalPages, 'totalItems' => $totalItems],
        ];
    }
    
    public function findById(int $id): ?array
    {
        $sql = "SELECT a.*, p.nom_entreprise, p.adresse, p.ville, p.latitude, p.longitude, p.telephone, c.nom AS categorie_nom
                FROM annonces a
                JOIN partenaires p ON a.partenaire_id = p.id
                JOIN categories c ON a.categorie_id = c.id
                WHERE a.id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    
    public function create(array $data): bool
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO annonces (partenaire_id, titre, description, description_courte, categorie_id, reduction, type_reduction, conditions, date_debut, date_fin, statut) 
                    VALUES (:partenaire_id, :titre, :description, :description_courte, :categorie_id, :reduction, :type_reduction, :conditions, :date_debut, :date_fin, :statut)";
            
            $this->db->query($sql, $data);
            $annonceId = $this->db->lastInsertId();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Erreur lors de la création de l'annonce: " . $e->getMessage());
            return false;
        }
    }
    
    public function update(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE annonces SET titre = :titre, description = :description, description_courte = :description_courte, 
                    categorie_id = :categorie_id, reduction = :reduction, type_reduction = :type_reduction, 
                    conditions = :conditions, date_debut = :date_debut, date_fin = :date_fin 
                    WHERE id = :id";
            
            $data['id'] = $id;
            $this->db->query($sql, $data);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour de l'annonce: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM annonces WHERE id = :id";
            $this->db->query($sql, ['id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de l'annonce: " . $e->getMessage());
            return false;
        }
    }
}
