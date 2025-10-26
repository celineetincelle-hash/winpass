<?php
// src/Utils/FileUploadValidator.php
class FileUploadValidator {
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp'
    ];
    
    // 5MB par défaut, configurable via .env
    private static function getMaxFileSize() {
        require_once __DIR__ . '/../../config/Env.php';
        return (int) Env::get('MAX_UPLOAD_SIZE', 5242880);
    }
    
    public static function validate($file) {
        $errors = [];
        $maxSize = self::getMaxFileSize();
        
        // 1. Vérifier erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = self::getUploadErrorMessage($file['error']);
            return ['valid' => false, 'errors' => $errors];
        }
        
        // 2. Vérifier la taille
        if ($file['size'] > $maxSize) {
            $errors[] = 'Fichier trop volumineux (max ' . self::formatBytes($maxSize) . ')';
        }
        
        if ($file['size'] === 0) {
            $errors[] = 'Le fichier est vide';
        }
        
        // 3. Vérifier l'extension
        $filename = strtolower($file['name']);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            $errors[] = 'Extension non autorisée. Extensions acceptées: ' . implode(', ', self::ALLOWED_EXTENSIONS);
        }
        
        // 4. Vérifier le type MIME déclaré
        if (!in_array($file['type'], self::ALLOWED_MIME_TYPES)) {
            $errors[] = 'Type de fichier non autorisé';
        }
        
        // 5. CRITIQUE: Vérifier le VRAI type MIME
        if (!function_exists('finfo_open')) {
            error_log('⚠️ Extension finfo non disponible - validation limitée');
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $realMimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($realMimeType, self::ALLOWED_MIME_TYPES)) {
                $errors[] = 'Le contenu du fichier ne correspond pas à une image valide';
                error_log("⚠️ Type MIME suspect: déclaré={$file['type']}, réel=$realMimeType");
            }
        }
        
        // 6. Vérifier que c'est vraiment une image
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $errors[] = 'Le fichier n\'est pas une image valide';
        }
        
        // 7. Vérifier les dimensions (optionnel)
        if ($imageInfo !== false) {
            $maxWidth = 4000;
            $maxHeight = 4000;
            
            if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
                $errors[] = "Dimensions trop grandes (max {$maxWidth}x{$maxHeight}px)";
            }
        }
        
        // 8. Scanner le contenu pour patterns suspects
        $content = file_get_contents($file['tmp_name']);
        $suspiciousPatterns = [
            '/<\?php/i',
            '/<script/i',
            '/eval\(/i',
            '/base64_decode/i',
            '/system\(/i',
            '/exec\(/i'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $errors[] = 'Contenu suspect détecté dans le fichier';
                error_log("🚨 SÉCURITÉ: Pattern suspect détecté dans upload - IP: " . $_SERVER['REMOTE_ADDR']);
                break;
            }
        }
        
        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }
        
        return [
            'valid' => true,
            'extension' => $extension,
            'mime_type' => $realMimeType ?? $file['type'],
            'size' => $file['size'],
            'dimensions' => [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1]
            ]
        ];
    }
    
    public static function sanitizeFilename($filename) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // Nettoyer: garder seulement lettres, chiffres, tirets et underscores
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        $name = substr($name, 0, 50);
        
        // Générer un nom unique et impossible à deviner
        $uniqueName = bin2hex(random_bytes(16)) . '_' . time();
        
        if (!empty($name)) {
            $uniqueName .= '_' . $name;
        }
        
        return $uniqueName . '.' . strtolower($extension);
    }
    
    public static function getUploadPath() {
        require_once __DIR__ . '/../../config/Env.php';
        $path = Env::get('UPLOAD_PATH');
        
        if (empty($path)) {
            $path = dirname(dirname(__DIR__)) . '/www/uploads';
        }
        
        return rtrim($path, '/');
    }
    
    private static function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);