<?php

namespace App\DataFixtures;

use App\Model\Moderation;
use App\Repository\ModerationRepository;
use DateTime;
use App\Model\Post;
use App\Model\Role;
use App\Model\User;
use App\Model\Comment;
use App\Model\Category;
use Cocur\Slugify\Slugify;
use App\Repository\Repository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use App\Repository\RoleRepository;

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
        $db->exec('TRUNCATE TABLE role');
        $db->exec('TRUNCATE TABLE moderation');
        $db->exec('SET FOREIGN_KEY_CHECKS = 1');

        $repoRole = new RoleRepository();

        $admin = new Role();
        $admin->setName('Admin');

        $repoRole->createRole($admin);

        $membre = new Role();
        $membre->setName('Membre');

        $repoRole->createRole($membre);

        $repoUser = new UserRepository();

        $damien = new User();
        $damien
            ->setName("Damien")
            ->setEmail("damien@d-riviere.fr")
            ->setPassword(password_hash("password", PASSWORD_BCRYPT))
            ->setSlug($slugify->slugify($damien->getName()))
            ->setCreatedAt(new DateTime())
            ->setPicture("https://www.manga-news.com/public/images/pix/serie/9164/the-arms-peddler-visual-8.jpg")
            ->setRoleId($admin->getId());

        $repoUser->createUser($damien);

        $moderationRepo = new ModerationRepository();

        $attentePublie = new Moderation();
        $attentePublie->setStatus("En attente de publication");

        $moderationRepo->createStatus($attentePublie);

        $publie = new Moderation();
        $publie->setStatus("Publié");

        $moderationRepo->createStatus($publie);

        $signale = new Moderation();
        $signale->setStatus("Signalé");

        $moderationRepo->createStatus($signale);

        $modere = new Moderation();
        $modere->setStatus("Modéré");

        $moderationRepo->createStatus($modere);
        
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user
                ->setName($faker->name())
                ->setEmail($faker->email())
                ->setPassword(password_hash($faker->password(), PASSWORD_BCRYPT))
                ->setSlug($slugify->slugify($user->getName()))
                ->setCreatedAt(new DateTime())
                ->setPicture("http://image.jeuxvideo.com/avatar-md/default.jpg")
                ->setRoleId($membre->getId());

            $repoUser->createUser($user);
        }

        // Insert Post
        $posts = [];
     
        for ($i = 0; $i < 30; $i++) {
            $post = new Post();

            $post
                ->setTitle($faker->sentence(2, true))
                ->setIntroduction($faker->paragraph(2))
                ->setContent($faker->paragraph(16))
                ->setCreatedAt(new DateTime())
                ->setCoverImage($faker->imageUrl(750, 300))
                ->setSlug($slugify->slugify($post->getTitle()))
                ->setUserId($damien->getId());

            $repoPost = new PostRepository();
            $repoPost->createPost($post);

            $posts[] = $post->getId();

            // Insert comment in post
            for ($c = 0; $c < 10; $c++) {
                $comment = new Comment();

                $comment
                    ->setContent($faker->sentence(10))
                    ->setPostId($post->getId())
                    ->setUserId(mt_rand(1, 10))
                    ->setCreatedAt(new DateTime())
                    ->setStatusId(2);

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
                ->setName($faker->sentence(1, true))
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
