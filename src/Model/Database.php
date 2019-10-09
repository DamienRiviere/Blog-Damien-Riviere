<?php

namespace App\Model;

use PDO;

class Database
{

    public static function getPDO(): PDO
    {
        return new PDO(
            'mysql:dbname=blog_damien_riviere;host=127.0.0.1',
            'root',
            'root',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
