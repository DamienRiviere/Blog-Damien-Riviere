<?php

namespace App\DataFixtures;

use DateTime;
use App\Model\Post;
use App\Model\User;
use App\Model\Comment;
use App\Model\Category;
use Cocur\Slugify\Slugify;
use App\Repository\Repository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;

class Fixtures
{

    public function setData()
    {
        $db = Repository::getDb();
        $faker = \Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $db->exec('SET FOREIGN_KEY_CHECKS = 0');
        $db->exec('TRUNCATE TABLE post');
        $db->exec('TRUNCATE TABLE category');
        $db->exec('TRUNCATE TABLE post_category');
        $db->exec('TRUNCATE TABLE user');
        $db->exec('TRUNCATE TABLE comment');
        $db->exec('SET FOREIGN_KEY_CHECKS = 1');

        $repoUser = new UserRepository();

        $damien = new User();
        $damien
            ->setName("Damien")
            ->setEmail("damien@d-riviere.fr")
            ->setPassword(password_hash("password", PASSWORD_BCRYPT))
            ->setSlug($slugify->slugify($damien->getName()))
            ->setCreatedAt(new DateTime())
            ->setPicture("https://www.manga-news.com/public/images/pix/serie/9164/the-arms-peddler-visual-8.jpg");

        $repoUser->createUser($damien);

        for($i = 0; $i < 10; $i++) {
            $user = new User();

            $user
                ->setName($faker->name())
                ->setEmail($faker->email())
                ->setPassword(password_hash($faker->password(), PASSWORD_BCRYPT))
                ->setSlug($slugify->slugify($user->getName()))
                ->setCreatedAt(new DateTime())
                ->setPicture("http://image.jeuxvideo.com/avatar-md/default.jpg");

            $repoUser->createUser($user);
        }

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
                ->setSlug($slugify->slugify($post->getTitle()))
                ->setUserId($damien->getId());

            $repoPost = new PostRepository();
            $repoPost->createPost($post);

            $posts[] = $post->getId();

            for($c = 0; $c < 10; $c++) {
                $comment = new Comment();

                $comment
                    ->setContent($faker->sentence(10))
                    ->setPostId($post->getId())
                    ->setUserId(mt_rand(1 , 10))
                    ->setCreatedAt(new DateTime());

                $repoComment = new CommentRepository();
                $repoComment->createComment($comment);
            }
        }
        // End Post

        // Insert categories
        $categories = [];

        for ($i = 0; $i < 5; $i++) {
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
        foreach ($posts as $post) {
            $randCategories = $faker->randomElements($categories, rand(0, count($categories)));

            foreach ($randCategories as $category) {
                $db->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
            }
        }
    }
}
