<?php

namespace App\Repository;

use App\Services\Pagination;
use PDO;
use App\Model\Post;
use App\Model\User;
use App\Model\Comment;
use Exception;

class UserRepository extends Repository
{

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
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'role_id' => $user->getRoleId()
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
            SELECT u.id, u.name, u.email, u.slug, u.picture, u.created_at, u.role_id
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
    public function hydratePostWithUser(Post $post): void
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
            $postsById[$post->getUserId()] = $post;
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
        return $user;
    }

    public function findName(string $name)
    {
        $query = self::getDb()->prepare('SELECT * FROM user WHERE name = :name');
        $query->execute(['name' => $name]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $user = $query->fetch();
        return $user;
    }

    /**
     * Find all users with each role
     *
     * @return array
     * @throws Exception
     */
    public function findAllUsers()
    {
        $query = self::getDb()->prepare('SELECT * FROM user ORDER BY created_at DESC');
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $users = $query->fetchAll();
        (new RoleRepository())->hydrateUsersWithRole($users);
        return $users;
    }

    public function findUsersPaginated()
    {
        $paginated = new Pagination(
            "SELECT * FROM user ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->repository}",
            10
        );
        $users = $paginated->getItems($this->class);
        (new RoleRepository())->hydrateUsersWithRole($users);
        return [$users, $paginated];
    }

    public function updateEmail(User $email, int $id): void
    {
        $this->update([
            'email' => $email->getEmail()
        ], (int)$id);
    }

    public function updatePassword(User $password, int $id): void
    {
        $this->update([
            'password' => $password->getPassword()
        ], (int)$id);
    }

    public function updateRole(User $roleId, int $id): void
    {
        $this->update([
            'role_id' => $roleId->getRoleId()
        ], (int)$id);
    }
}
