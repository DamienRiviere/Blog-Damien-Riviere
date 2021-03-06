<?php

namespace App\Repository;

use App\Helpers\Session;
use PDO;

abstract class Repository
{

    /** @var PDO|null */
    protected static $db;

    protected $repository = null;

    protected $class = null;

    protected $session;

    public function __construct()
    {
        if ($this->repository === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$repository");
        }
        if ($this->class === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$class");
        }
        $this->session = new Session();
    }

    public static function getDb()
    {
        if (is_null(self::$db)) {
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
            $confDb['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

        return $database;
    }

    public function all($options = []): array
    {
        $query = self::getDb()->prepare("SELECT * FROM {$this->repository} " . implode('', $options));
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

        if ($result === false) {
            throw new \Exception("Ressource introuvable !");
        }

        return $result;
    }

    public function create(array $data): int
    {
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = self::getDb()->prepare("INSERT INTO {$this->repository} SET " . implode(', ', $sqlFields));
        $status = $query->execute($data);
        if ($status === false) {
            throw new Exception("Impossible de créer l'enregistrement dans la base de données");
        }
        return (int)self::getDb()->lastInsertId();
    }

    public function update(array $data, int $id)
    {
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = self::getDb()->prepare("
            UPDATE {$this->repository} 
            SET " . implode(', ', $sqlFields) . " 
            WHERE id = :id");
        $status = $query->execute(array_merge($data, ['id' => $id]));
        if ($status === false) {
            throw new Exception("Impossible de modifier l'enregistrement dans la base de données");
        }
    }

    public function delete(int $id)
    {
        $query = self::getDb()->prepare("DELETE FROM {$this->repository} WHERE id = ?");
        $status = $query->execute([$id]);
        if ($status === false) {
            throw new Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->repository}");
        }
    }

    public function hydrateOneObject($sql, $item, $id, $class, $add): void
    {
        $query = self::getDb()->prepare($sql);
        $query->execute([$id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $class);
        $hydrate = $query->fetch();

        $item->$add($hydrate);
    }
}
