<?php

namespace App\Repository;

use PDO;

abstract class Repository
{

    /** @var PDO|null */
    protected static $db;

    protected $repository = null;

    protected $class = null;

    public function __construct()
    {
        if ($this->repository === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$repository");
        }
        if ($this->class === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$class");
        }
    }

    public static function getDb()
    {
        if(is_null(self::$db)) {
            self::$db = self::configureDatabase();
        }

        return self::$db;
    }

    private static function configureDatabase()
    {
        $confDb = require(__DIR__ . '/../../config/database.php');

        $database = new PDO(
            sprintf("%s;dbname=%s", $confDb['dsn'], $confDb['dbname']),
            $confDb['user'],
            $confDb['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

        return $database;
    }

    public function all(): array
    {
        $query = self::getDb()->prepare("SELECT * FROM {$this->repository}");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetchAll();
        return $result;
    }

    public function find(int $id) 
    {
        $query = self::getDb()->prepare('SELECT * FROM ' . $this->repository . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        return $result;
    }

    public function create(array $data): int 
    {
        $sqlFields = [];
        foreach($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = self::getDb()->prepare("INSERT INTO {$this->repository} SET " . implode(', ', $sqlFields));
        $status = $query->execute($data);
        if($status === false) {
            throw new Exception("Impossible de créer l'enregistrement dans la base de données");
        }
        return (int)self::getDb()->lastInsertId();
    }

}
