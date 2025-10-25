<?php
declare(strict_types=1);

namespace App\Models;

use Core\Database;

class CarteModel
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getActiveCarteForUser(int $userId): ?array
    {
        $sql = "SELECT * FROM cartes WHERE utilisateur_id = :userId AND statut = 'actif' AND date_fin >= CURDATE() ORDER BY date_fin DESC LIMIT 1";
        $stmt = $this->db->query($sql, ['userId' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function createCarte(int $userId, string $type, float $prix): bool
    {
        try {
            $this->db->beginTransaction();
            
            $durationDays = match($type) {
                'semaine' => 7, 
                'deux_semaines' => 14, 
                'mois' => 30, 
                'annee' => 365, 
                default => 30
            };
            
            $dateDebut = date('Y-m-d');
            $dateFin = date('Y-m-d', strtotime("+$durationDays days"));

            $sql = "INSERT INTO cartes (utilisateur_id, type_abonnement, date_debut, date_fin, prix, statut) 
                    VALUES (:userId, :type, :dateDebut, :dateFin, :prix, 'actif')";
            
            $this->db->query($sql, [
                'userId' => $userId,
                'type' => $type,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'prix' => $prix
            ]);
            
            $carteId = $this->db->lastInsertId();
            
            // Créer un enregistrement de paiement
            $sql = "INSERT INTO paiements (utilisateur_id, carte_id, montant, methode_paiement, statut, date_paiement) 
                    VALUES (:userId, :carteId, :montant, :methode, 'complete', NOW())";
            
            $this->db->query($sql, [
                'userId' => $userId,
                'carteId' => $carteId,
                'montant' => $prix,
                'methode' => 'simulation'
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Erreur lors de la création de la carte: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserCartes(int $userId): array
    {
        $sql = "SELECT * FROM cartes WHERE utilisateur_id = :userId ORDER BY date_achat DESC";
        return $this->db->query($sql, ['userId' => $userId])->fetchAll();
    }
    
    public function expireOldCartes(): int
    {
        $sql = "UPDATE cartes SET statut = 'expire' WHERE statut = 'actif' AND date_fin < CURDATE()";
        $this->db->query($sql);
        return $this->db->getInstance()->rowCount();
    }
}
