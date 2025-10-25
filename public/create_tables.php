<?php
// public/create_tables.php
echo "<!DOCTYPE html><html><head><title>Création des Tables</title></head><body>";
echo "<h1>Création des Tables de la Base de Données</h1>";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=winpask2025.mysql.db;dbname=winpask2025", "winpask2025", "7777Jump7777");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Connexion à la base de données réussie</p>";
    
    // Script SQL de création des tables
    $sql = "
    -- Création des tables
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        description TEXT,
        icone VARCHAR(255),
        couleur VARCHAR(7) DEFAULT '#0066cc',
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        telephone VARCHAR(20),
        date_naissance DATE,
        type ENUM('user', 'partner', 'admin') DEFAULT 'user',
        date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        derniere_connexion DATETIME,
        statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
        photo_profil VARCHAR(255),
        remise_cumulee DECIMAL(5,2) DEFAULT 0.00
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS partenaires (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        nom_entreprise VARCHAR(255) NOT NULL,
        siret VARCHAR(14) NOT NULL UNIQUE,
        adresse VARCHAR(255) NOT NULL,
        code_postal VARCHAR(5) NOT NULL,
        ville VARCHAR(100) NOT NULL,
        latitude DECIMAL(10, 8),
        longitude DECIMAL(11, 8),
        telephone VARCHAR(20),
        email_contact VARCHAR(255),
        site_web VARCHAR(255),
        description TEXT,
        logo VARCHAR(255),
        photos JSON,
        horaires JSON,
        statut ENUM('actif', 'inactif', 'en_attente') DEFAULT 'en_attente',
        date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS annonces (
        id INT AUTO_INCREMENT PRIMARY KEY,
        partenaire_id INT NOT NULL,
        titre VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        description_courte VARCHAR(255),
        categorie_id INT NOT NULL,
        reduction DECIMAL(5,2) NOT NULL,
        type_reduction ENUM('pourcentage', 'montant_fixe') DEFAULT 'pourcentage',
        conditions TEXT,
        date_debut DATE NOT NULL,
        date_fin DATE NOT NULL,
        photos JSON,
        statut ENUM('actif', 'inactif', 'en_attente') DEFAULT 'en_attente',
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (partenaire_id) REFERENCES partenaires(id) ON DELETE CASCADE,
        FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS cartes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        type_abonnement ENUM('semaine', 'deux_semaines', 'mois', 'annee') NOT NULL,
        date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
        date_debut DATE NOT NULL,
        date_fin DATE NOT NULL,
        statut ENUM('actif', 'expire', 'annule') DEFAULT 'actif',
        prix DECIMAL(10,2) NOT NULL,
        methode_paiement VARCHAR(50),
        transaction_id VARCHAR(255),
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS qr_codes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT,
        carte_id INT,
        annonce_id INT,
        code VARCHAR(255) NOT NULL UNIQUE,
        type ENUM('connexion', 'carte', 'utilisation') NOT NULL,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        date_expiration DATETIME,
        utilise BOOLEAN DEFAULT FALSE,
        date_utilisation DATETIME,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
        FOREIGN KEY (carte_id) REFERENCES cartes(id) ON DELETE CASCADE,
        FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS paiements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        carte_id INT,
        montant DECIMAL(10,2) NOT NULL,
        methode_paiement VARCHAR(50) NOT NULL,
        statut ENUM('en_attente', 'complete', 'echoue', 'rembourse') DEFAULT 'en_attente',
        transaction_id VARCHAR(255),
        date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
        FOREIGN KEY (carte_id) REFERENCES cartes(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        titre VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
        lue BOOLEAN DEFAULT FALSE,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id INT NOT NULL,
        token VARCHAR(255) NOT NULL UNIQUE,
        type ENUM('session', 'reset_password', 'qr_login') DEFAULT 'session',
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME NOT NULL,
        last_used DATETIME,
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Exécuter le script SQL
    $pdo->exec($sql);
    
    echo "<p style='color: green;'>✅ Tables créées avec succès !</p>";
    
    // Insérer les catégories par défaut
    $categories = [
        ['Restaurant', 'Établissements de restauration', 'utensils', '#FF6B6B'],
        ['Shopping', 'Boutiques et magasins', 'shopping-bag', '#4ECDC4'],
        ['Loisirs', 'Activités de divertissement', 'gamepad', '#45B7D1'],
        ['Services', 'Services professionnels', 'concierge-bell', '#96CEB4'],
        ['Hôtels', 'Hébergement touristique', 'bed', '#3B82F6'],
        ['Transport', 'Services de transport', 'car', '#F59E0B']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO categories (nom, description, icone, couleur) VALUES (?, ?, ?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    
    echo "<p style='color: green;'>✅ Catégories par défaut insérées</p>";
    
    // Créer un utilisateur admin par défaut
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, password, type, statut) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['Admin', 'WinPass', 'admin@winpass.tn', $admin_password, 'admin', 'actif']);
    
    echo "<p style='color: green;'>✅ Utilisateur admin créé (admin@winpass.tn / admin123)</p>";
    
    // Vérification des tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tables créées (" . count($tables) . ") :</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li style='color: green;'>✅ $table</li>";
    }
    echo "</ul>";
    
    echo "<p><a href='?test=complete'>Test complet du système</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Test complet si demandé
if (isset($_GET['test']) && $_GET['test'] == 'complete') {
    echo "<h2>Test Complet du Système</h2>";
    
    try {
        // Test du modèle Annonce
        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../core/Database.php';
        require_once __DIR__ . '/../app/Models/AnnonceModel.php';
        
        $db = new Core\Database();
        $annonceModel = new \App\Models\AnnonceModel($db);
        $annonces = $annonceModel->getFeaturedAnnonces(3);
        
        echo "<p style='color: green;'>✅ Modèle Annonce fonctionnel</p>";
        echo "<p>Annonces trouvées : " . count($annonces) . "</p>";
        
        if (count($annonces) == 0) {
            echo "<p style='color: orange;'>⚠️ Aucune annonce trouvée (normal pour le moment)</p>";
            
            // Créer une annonce de test
            $test_data = [
                'partenaire_id' => 1,
                'titre' => 'Test Restaurant',
                'description' => 'Description de test pour notre restaurant',
                'description_courte' => 'Restaurant de test',
                'categorie_id' => 1,
                'reduction' => 15.00,
                'type_reduction' => 'pourcentage',
                'conditions' => 'Valable sur présentation de la carte WinPass',
                'date_debut' => date('Y-m-d'),
                'date_fin' => date('Y-m-d', strtotime('+30 days')),
                'statut' => 'actif'
            ];
            
            $stmt = $pdo->prepare("INSERT INTO annonces (partenaire_id, titre, description, description_courte, categorie_id, reduction, type_reduction, conditions, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute($test_data);
            
            echo "<p style='color: green;'>✅ Annonce de test créée</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Erreur test : " . $e->getMessage() . "</p>";
    }
}

echo "</body></html>";
?>