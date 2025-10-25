<?php
// core/Database.php
declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    public function __construct()
    {
        // Constructeur public pour permettre l'instanciation
    }

    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            if (empty(self::$config)) {
                if (file_exists(__DIR__ . '/../includes/config.php')) {
                    require_once __DIR__ . '/../includes/config.php';
                    self::$config = [
                        'host' => DB_HOST,
                        'name' => DB_NAME,
                        'user' => DB_USER,
                        'pass' => DB_PASS
                    ];
                } else {
                    throw new PDOException('Configuration de la base de données non trouvée');
                }
            }
            
            $dsn = 'mysql:host=' . self::$config['host'] . ';dbname=' . self::$config['name'] . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            try {
                self::$instance = new PDO($dsn, self::$config['user'], self::$config['pass'], $options);
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données: " . $e->getMessage());
                throw new PDOException("Erreur de connexion à la base de données", (int)$e->getCode());
            }
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $stmt = self::getInstance()->prepare($sql);
            foreach ($params as $key => $value) {
                $paramType = is_int($value) ? PDO::PARAM_INT : 
                             (is_bool($value) ? PDO::PARAM_BOOL : PDO::PARAM_STR);
                $stmt->bindValue(
                    is_int($key) ? $key + 1 : ':' . $key,
                    $value,
                    $paramType
                );
            }
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erreur de requête: " . $e->getMessage() . " SQL: " . $sql);
            throw $e;
        }
    }

    public function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return self::getInstance()->beginTransaction();
    }

    public function commit(): bool
    {
        return self::getInstance()->commit();
    }

    public function rollback(): bool
    {
        return self::getInstance()->rollback();
    }
}