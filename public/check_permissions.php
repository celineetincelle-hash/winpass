<?php
// www/public/check_permissions.php
echo "<h1>Vérification des Permissions</h1>";

 $paths = [
    __DIR__ . '/../includes/config.php',
    __DIR__ . '/../logs/',
    __DIR__ . '/../public/uploads/',
    __DIR__ . '/../vendor/'
];

foreach ($paths as $path) {
    $exists = file_exists($path) ? "✅" : "❌";
    $readable = is_readable($path) ? "✅" : "❌";
    $writable = is_writable($path) ? "✅" : "❌";
    
    echo "<p><strong>$path</strong><br>";
    echo "Existe : $exists | Lecture : $readable | Écriture : $writable</p>";
    
    if (is_dir($path)) {
        echo "<ul>";
        foreach (scandir($path) as $file) {
            if ($file != '.' && $file != '..') {
                $filepath = $path . '/' . $file;
                $file_readable = is_readable($filepath) ? "✅" : "❌";
                echo "<li>$file : $file_readable</li>";
            }
        }
        echo "</ul>";
    }
}

// Test d'écriture
 $test_file = __DIR__ . '/../logs/test_write.txt';
if (file_put_contents($test_file, "test")) {
    echo "<p>✅ Test d'écriture réussi</p>";
    unlink($test_file);
} else {
    echo "<p>❌ Test d'écriture échoué</p>";
}
?>