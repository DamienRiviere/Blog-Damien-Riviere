<?php

namespace App\Repository;

use PDO;
use App\Model\Database;

abstract class Repository {

    protected $db;

    protected $repository = null;

    protected $class = null;

    public function __construct() {
        if($this->repository === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$repository");
        }
        if($this->class === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$class");
        }
        $this->db = Database::getPDO();
    }

    public function all(): array {
        $sql = "SELECT * FROM {$this->repository}";
        return $this->db->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }
}