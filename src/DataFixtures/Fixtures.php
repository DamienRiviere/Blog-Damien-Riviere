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

    private $db;

    private $faker;

    public function __construct()
    {
        $this->db = (Repository::getDb());
        $this->faker = \Faker\Factory::create('fr_FR');
    }

    public function setData()
    {
        $slugify = new Slugify();

        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->exec('TRUNCATE TABLE post');
        $this->db->exec('TRUNCATE TABLE category');
        $this->db->exec('TRUNCATE TABLE post_category');
        $this->db->exec('TRUNCATE TABLE user');
        $this->db->exec('TRUNCATE TABLE comment');
        $this->db->exec('TRUNCATE TABLE role');
        $this->db->exec('TRUNCATE TABLE moderation');
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');

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
                ->setName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setPassword(password_hash($this->faker->password(), PASSWORD_BCRYPT))
                ->setSlug($slugify->slugify($user->getName()))
                ->setCreatedAt(new DateTime())
                ->setPicture("http://image.jeuxvideo.com/avatar-md/default.jpg")
                ->setRoleId($membre->getId());

            $repoUser->createUser($user);
        }

        // Insert Data
        $posts = [];
     
        for ($i = 0; $i < 30; $i++) {
            $post = new Post();

            $post
                ->setTitle($this->faker->sentence(2, true))
                ->setIntroduction($this->faker->paragraph(2))
                ->setContent($this->faker->paragraph(16))
                ->setCreatedAt(new DateTime())
                ->setCoverImage($this->faker->imageUrl(750, 300))
                ->setSlug($slugify->slugify($post->getTitle()))
                ->setUserId($damien->getId());

            $repoPost = new PostRepository();
            $repoPost->createPost($post);

            $posts[] = $post->getId();

            // Insert comment in post
            for ($c = 0; $c < 10; $c++) {
                $comment = new Comment();

                $comment
                    ->setContent($this->faker->sentence(10))
                    ->setPostId($post->getId())
                    ->setUserId(mt_rand(1, 10))
                    ->setCreatedAt(new DateTime())
                    ->setStatusId(2);

                $repoComment = new CommentRepository();
                $repoComment->createComment($comment);
            }
        }
        // End Data

        // Insert categories
        $categories = [];

        for ($i = 0; $i < 5; $i++) {
            $category = new Category();

            $category
                ->setName($this->faker->sentence(1, true))
                ->setSlug($slugify->slugify($category->getName()))
                ->setStyle("badge badge-danger");

            $repoCategory = new CategoryRepository();
            $repoCategory->createCategory($category);
            
            $categories[] = $category->getId();
        }
        // End categories

        // Attach a post to one or more categories
        foreach ($posts as $post) {
            $randCategories = $this->faker->randomElements($categories, rand(0, count($categories)));

            foreach ($randCategories as $category) {
                $this->db->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
            }
        }
    }
}
