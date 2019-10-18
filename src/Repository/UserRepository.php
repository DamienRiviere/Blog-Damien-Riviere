<?php

namespace App\Repository;

use PDO;
use App\Model\User;
use App\Model\Comment;

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
        $query = self::getDb()->prepare('
            SELECT u.*
            FROM user u
            JOIN comment c ON u.id = c.user_id
            WHERE c.user_id = ?
        ');
        
        $query->execute([$comment->getUserId()]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $user = $query->fetch();

        $comment->addUser($user);
    }
}