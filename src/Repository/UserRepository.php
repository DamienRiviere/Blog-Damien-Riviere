<?php

namespace App\Repository;

use PDO;
use App\Model\Post;
use App\Model\User;
use App\Model\Comment;
use Exception;

class UserRepository extends Repository {

    protected $repository = "user";

    protected $class = User::class;

    public function createUser(User $user): void
    {
        $id = $this->create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'slug' => $user->getSlug(),
            'picture' => $user->getPicture(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
        $user->setId($id);
    }

    /**
     * Hydrate a comment with his user
     *
     * @param Comment $comment
     * @return void
     */
    public function hydrateCommentWithUser(Comment $comment): void
    {
        $sql = '
            SELECT u.*
            FROM user u
            JOIN comment c ON u.id = c.user_id
            WHERE c.user_id = ?
        ';       

        $this->hydrateOneObject($sql, $comment, $comment->getUserId(), $this->class, "addUser");
    }

    /**
     * Hydrate a post with his user
     *
     * @param Post $post
     * @return void
     */
    public function hydratePostWithUser($post): void
    {
        $sql = '
            SELECT u.*
            FROM user u
            JOIN post p ON u.id = p.user_id
            WHERE p.user_id = ?
        ';

        $this->hydrateOneObject($sql, $post, $post->getUserId(), $this->class, "addUser");
    }

    /**
     * Hydrate several posts with his user
     *
     * @param array|null $posts
     * @return void
     */
    public function hydratePostsWithUser(?array $posts): void
    {
        $postsById = [];
        foreach ($posts as $post) {
            $postsById[$post->getId()] = $post;
        }

        $query = self::getDb()->prepare('
            SELECT u.*
            FROM user u
            JOIN post p ON u.id = p.user_id
            WHERE p.user_id 
            IN (' . implode(',', array_keys($postsById)) . ')
        ');

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $user = $query->fetch();

        foreach ($posts as $post) {
            $post->addUser($user);       
        }
    }

    public function findEmail(string $email)
    {
        $query = self::getDb()->prepare('SELECT * FROM user WHERE email = :email');
        $query->execute(['email' => $email]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $user = $query->fetch();
        if($user === false) {
            throw new Exception("Utilisateur introuvable");
        }
        return $user;
    }
}