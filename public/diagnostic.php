<?php
// public/diagnostic_ovh.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic OVH WinPass</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
        .box { border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Diagnostic OVH WinPass Advanced</h1>
    
    <div class="box">
        <h2>1. Informations PHP</h2>
        <p>Version PHP : <span class="<?php echo version_compare(PHP_VERSION, '8.0.0', '>=') ? 'success' : 'error'; ?>"><?php echo PHP_VERSION; ?></span></p>
        <p>Memory Limit : <?php echo ini_get('memory_limit'); ?></p>
        <p>Max Execution Time : <?php echo ini_get('max_execution_time'); ?>s</p>
        <p>Upload Max Filesize : <?php echo ini_get('upload_max_filesize'); ?></p>
        <p>Display Errors : <?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></p>
        <p>Log Errors : <?php echo ini_get('log_errors') ? 'On' : 'Off'; ?></p>
    </div>
    
    <div class="box">
        <h2>2. Extensions Requises</h2>
        <?php
        $required_extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'gd', 'curl'];
        foreach ($required_extensions as $ext) {
            $loaded = extension_loaded($ext);
            echo "<p class='" . ($loaded ? 'success' : 'error') . "'>" . ($loaded ? '✅' : '❌') . " $ext</p>";
        }
        ?>
    </div>
    
    <div class="box">
        <h2>3. Structure des Fichiers</h2>
        <?php
        $base_dir = __DIR__ . '/..';
        $required_structure = [
            'includes/config.php' => 'Configuration',
            'core/Database.php' => 'Base de données',
            'core/Session.php' => 'Sessions',
            'vendor/autoload.php' => 'Autoloader Composer'
        ];
        
        foreach ($required_structure as $file => $description) {
            $exists = file_exists($base_dir . '/' . $file);
            echo "<p class='" . ($exists ? 'success' : 'error') . "'>" . ($exists ? '✅' : '❌') . " $description : $file</p>";
        }
        ?>
    </div>
    
    <div class="box">
        <h2>4. Test de Connexion BDD</h2>
        <?php
        try {
            if (file_exists($base_dir . '/includes/config.php')) {
                require_once $base_dir . '/includes/config.php';
                $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                echo "<p class='success'>✅ Connexion à la base de données réussie</p>";
                
                // Test de table
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo "<p class='info'>Tables trouvées (" . count($tables) . ") : " . implode(', ', array_slice($tables, 0, 5));
                if (count($tables) > 5) echo "...";
                echo "</p>";
                
                // Test de requête
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
                $count = $stmt->fetchColumn();
                echo "<p class='info'>Categories dans la BDD : $count</p>";
                
            } else {
                echo "<p class='error'>❌ Fichier de configuration non trouvé</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur de connexion : " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <div class="box">
        <h2>5. Permissions</h2>
        <?php
        $dirs_to_check = [$base_dir . '/logs', $base_dir . '/public/uploads'];
        foreach ($dirs_to_check as $dir) {
            if (is_dir($dir)) {
                $writable = is_writable($dir);
                echo "<p class='" . ($writable ? 'success' : 'error') . "'>Écriture : " . basename($dir) . "</p>";
            } else {
                echo "<p class='warning'>⚠️ Dossier manquant : " . basename($dir) . "</p>";
            }
        }
        ?>
    </div>
    
    <div class="box">
        <h2>6. Configuration .htaccess</h2>
        <?php
        $htaccess = __DIR__ . '/.htaccess';
        if (file_exists($htaccess)) {
            echo "<p class='success'>✅ Fichier .htaccess trouvé</p>";
            echo "<pre>" . htmlspecialchars(file_get_contents($htaccess)) . "</pre>";
        } else {
            echo "<p class='error'>❌ Fichier .htaccess manquant</p>";
        }
        ?>
    </div>
    
    <div class="box">
        <h2>7. Test du Modèle Annonce</h2>
        <?php
        try {
            require_once $base_dir . '/vendor/autoload.php';
            require_once $base_dir . '/core/Database.php';
            require_once $base_dir . '/app/Models/AnnonceModel.php';
            
            $db = new Core\Database();
            $annonceModel = new \App\Models\AnnonceModel($db);
            $annonces = $annonceModel->getFeaturedAnnonces(3);
            
            echo "<p class='success'>✅ Modèle Annonce fonctionnel</p>";
            echo "<p class='info'>Annonces trouvées : " . count($annonces) . "</p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erreur modèle : " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <div class="box">
        <h2>🎯 Actions Recommandées</h2>
        <ul>
            <li>Si des extensions manquent, contactez le support