<?php

namespace App\DataFixtures;

use App\Model\Database;
use Cocur\Slugify\Slugify;

class Fixtures {
    public static function setPosts() {
        $db = Database::getPDO();
        $faker = \Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $db->exec('SET FOREIGN_KEY_CHECKS = 0');
        $db->exec('TRUNCATE TABLE post');
        
        $posts = [];

        for($i = 0; $i < 30; $i++) {
            $db->exec("INSERT INTO post SET 
                title='{$faker->sentence()}', 
                introduction='{$faker->paragraph(4)}', 
                content='{$faker->paragraph(8)}', 
                created_at='{$faker->date()} {$faker->time()}', 
                cover_image='{$faker->imageUrl(750, 300)}', 
                slug='{$slugify->slugify($faker->sentence())}'
            ");
            $posts[] = $db->lastInsertId();
        }
    }
}
