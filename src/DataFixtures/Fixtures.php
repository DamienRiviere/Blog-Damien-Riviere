<?php

namespace App\DataFixtures;

use App\Model\Category;
use App\Model\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\Repository;
use Cocur\Slugify\Slugify;
use DateTime;

class Fixtures
{

    public function setPosts()
    {
        $db = Repository::getDb();
        $faker = \Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $db->exec('SET FOREIGN_KEY_CHECKS = 0');
        $db->exec('TRUNCATE TABLE post');
        $db->exec('TRUNCATE TABLE category');
        $db->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Insert Post
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

            $repoPost = new PostRepository();
            $repoPost->createPost($post);

            $posts[] = $post->getId();
        }
        // End Post

        // Insert categories
        $categories = [];

        for($i = 0; $i < 5; $i++) {
            $category = new Category();

            $category
                ->setName($faker->sentence(1))
                ->setSlug($slugify->slugify($category->getName()))
                ->setStyle("badge badge-danger");

            $repoCategory = new CategoryRepository();
            $repoCategory->createCategory($category);
            
            $categories[] = $category->getId();
        }
        // End categories

        // Attach a post to one or more categories 
        foreach($posts as $post) {
            $randCategories = $faker->randomElements($categories, rand(0, count($categories)));

            foreach($randCategories as $category) {
                $db->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
            }
        }
    }
}
