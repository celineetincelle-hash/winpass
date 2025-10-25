<?php
declare(strict_types=1);

namespace App\Models;

use Core\Database;

class UserModel
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $this->db->query($sql, ['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO utilisateurs (nom, prenom, email, password, telephone, type, date_inscription, statut) 
                    VALUES (:nom, :prenom, :email, :password, :telephone, :type, NOW(), :statut)";
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $params = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'telephone' => $data['telephone'] ?? null,
                'type' => $data['type'] ?? 'user',
                'statut' => $data['statut'] ?? 'actif'
            ];
            
            $this->db->query($sql, $params);
            $userId = $this->db->lastInsertId();
            
            // Si c'est un partenaire, créer l'entrée correspondante
            if ($data['type'] === 'partner' && isset($data['partenaire'])) {
                $partenaireData = $data['partenaire'];
                $partenaireData['utilisateur_id'] = $userId;
                
                $sql = "INSERT INTO partenaires (utilisateur_id, nom_entreprise, siret, adresse, code_postal, ville, telephone, email_contact, site_web, description, statut) 
                        VALUES (:utilisateur_id, :nom_entreprise, :siret, :adresse, :code_postal, :ville, :telephone, :email_contact, :site_web, :description, :statut)";
                
                $this->db->query($sql, $partenaireData);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateProfile(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, telephone = :telephone WHERE id = :id";
            $data['id'] = $id;
            $this->db->query($sql, $data);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du profil: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers(int $page = 1, int $limit = 20): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT id, nom, prenom, email, type, statut, date_inscription FROM utilisateurs ORDER BY date_inscription DESC LIMIT :limit OFFSET :offset";
        $users = $this->db->query($sql, ['limit' => $limit, 'offset' => $offset])->fetchAll();
        
        $countSql = "SELECT COUNT(*) as total FROM utilisateurs";
        $totalItems = (int)$this->db->query($countSql)->fetchColumn();
        $totalPages = (int)ceil($totalItems / $limit);

        return ['users' => $users, 'pagination' => ['currentPage' => $page, 'totalPages' => $totalPages, 'totalItems' => $totalItems]];
    }

    public function updateStatus(int $id, string $status): bool
    {
        try {
            $sql = "UPDATE utilisateurs SET statut = :statut WHERE id = :id";
            $this->db->query($sql, ['statut' => $status, 'id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du statut: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateLastLogin(int $id): bool
    {
        try {
            $sql = "UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = :id";
            $this->db->query($sql, ['id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour de la dernière connexion: " . $e->getMessage());
            return false;
        }
    }
    
    public function verifyPassword(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Mettre à jour la dernière connexion
            $this->updateLastLogin($user['id']);
            return $user;
        }
        return null;
    }
}
