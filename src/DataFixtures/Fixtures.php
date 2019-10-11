<?php

namespace App\DataFixtures;

use App\Model\Post;
use App\Model\Database;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use DateTime;

class Fixtures
{
    public static function setPosts()
    {
        $db = Database::getPDO();
        $faker = \Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $db->exec('SET FOREIGN_KEY_CHECKS = 0');
        $db->exec('TRUNCATE TABLE post');
        
        $posts = [];

        for ($i = 0; $i < 30; $i++) {
            $post = new Post();

            $post
                ->setTitle($faker->sentence())
                ->setIntroduction($faker->paragraph(4))
                ->setContent($faker->paragraph(8))
                ->setCreatedAt(new DateTime())
                ->setCoverImage($faker->imageUrl(750, 300))
                ->setSlug($slugify->slugify($post->getTitle()));

            $repo = new PostRepository();
            $repo->createPost($post);
        }
    }
}
