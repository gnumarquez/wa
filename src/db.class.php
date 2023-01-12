<?php
namespace Gnumarquez;

class DB
{
    public $pdo;
    
    public function __construct($db, $user, $pass, $host)
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        $dsn = "mysql:host=$host;dbname=$db;port=3306;charset=utf8mb4";

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $default_options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function run($sql, $args = NULL)
    {
        if (!$args)
        {
            return $this->pdo->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}

?>