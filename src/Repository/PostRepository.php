<?php

namespace App\Repository;

use App\Model\Comment;
use App\Services\Pagination;
use PDO;
use App\Model\Post;
use App\Repository\UserRepository;
use Exception;

class PostRepository extends Repository
{

    protected $repository = "post";

    protected $class = Post::class;

    public function createPost(Post $post): void
    {
        $id = $this->create([
            'title' => $post->getTitle(),
            'introduction' => $post->getIntroduction(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'cover_image' => $post->getCoverImage(),
            'slug' => $post->getSlug(),
            'user_id' => $post->getUserId()
        ]);
        $post->setId($id);
    }

    public function updatePost(Post $post): void
    {
        $this->update([
            'title' => $post->getTitle(),
            'introduction' => $post->getIntroduction(),
            'content' => $post->getContent(),
            'modify_at' => $post->getModifyAt()->format('Y-m-d H:i:s'),
            'cover_image' => $post->getCoverImage(),
            'slug' => $post->getSlug()
        ], $post->getId());
    }

    public function findAllPosts()
    {
        $query = self::getDb()->prepare("SELECT * FROM {$this->repository} ORDER BY created_at DESC");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $posts = $query->fetchAll();
        (new CategoryRepository())->hydratePostsWithCategories($posts);
        (new UserRepository())->hydratePostsWithUser($posts);
        return $posts;
    }

    public function findPost(int $id)
    {
        $query = self::getDb()->prepare('SELECT * FROM ' . $this->repository . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $post = $query->fetch();
        if ($post === false) {
            throw new Exception("L'article que vous recherchez est introuvable !");
        }
        (new CategoryRepository())->hydratePostsWithCategories([$post]);
        (new UserRepository())->hydratePostWithUser($post);
        return $post;
    }

    public function findPostByCategory(int $id)
    {
        $query = self::getDb()->prepare("
            SELECT * FROM {$this->repository} 
            JOIN post_category pc ON pc.post_id = {$this->repository}.id 
            WHERE pc.category_id = :id 
            ORDER BY created_at DESC
        ");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $posts = $query->fetchAll();
        (new CategoryRepository())->hydratePostsWithCategories($posts);
        return $posts;
    }

    /**
     * Add a category to a post
     *
     * @param integer $id
     * @param array $categories
     * @return void
     */
    public function attachCategoriesToPost(int $id, array $categories)
    {
        self::getDb()->exec('DELETE FROM post_category WHERE post_id = ' . $id);
        $query = self::getDb()->prepare('INSERT INTO post_category SET post_id = ?, category_id = ?');
        foreach ($categories as $category) {
            $query->execute([$id, $category]);
        }
    }

    /**
     * Find posts with pagination
     *
     * @return array
     * @throws Exception
     */
    public function findPostsPaginated()
    {
        $paginated = new Pagination(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->repository}"
        );
        $posts = $paginated->getItems($this->class);

        (new CategoryRepository())->hydratePostsWithCategories($posts);
        (new UserRepository())->hydratePostsWithUser($posts);
        return [$posts, $paginated];
    }

    /**
     * Find posts by category with pagination
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function findPostsPaginatedByCategory(int $id)
    {
        $paginated = new Pagination(
            "SELECT * FROM {$this->repository} p
            JOIN post_category pc ON pc.post_id = p.id 
            WHERE pc.category_id = ?
            ORDER BY created_at DESC",
            "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$id}",
            8,
            $id
        );
        $posts = $paginated->getItems($this->class);

        if (empty($posts)) {
            throw new Exception("Cette catégorie n'existe pas !");
        }

        (new CategoryRepository())->hydratePostsWithCategories($posts);
        return [$posts, $paginated];
    }

    public function findLastPosts()
    {
        $query = self::getDb()->prepare("SELECT * FROM {$this->repository} ORDER BY created_at DESC LIMIT 3");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $posts = $query->fetchAll();

        return $posts;
    }

    public function findCountPostsByUser($id)
    {
        $query = self::getDb()->prepare("
			SELECT COUNT(id)
			FROM {$this->repository}
			WHERE user_id = ?
		");
        $query->execute([$id]);
        $count = $query->fetch();
        return $count[0];
    }

    /**
     * Find the total number of posts for dashboard
     *
     * @return mixed
     */
    public function findCountPosts()
    {
        $query = self::getDb()->prepare("
			SELECT COUNT(id)
			FROM {$this->repository}
		");
        $query->execute();
        $count = $query->fetch();
        return $count[0];
    }

    /**
     * Find all posts liked by the user connected
     *
     * @param $id
     * @return array
     * @throws Exception
     */
    public function findPostsLikeByUser($id)
    {
        $paginated = new Pagination(
            "SELECT p.*, pl.user_id
			FROM post_like pl
			JOIN post p ON p.id = pl.post_id
			WHERE pl.user_id = ?
			ORDER BY p.created_at DESC",
            "SELECT COUNT(user_id) FROM post_like WHERE user_id = ?",
            5,
            $id
        );
        $posts = $paginated->getItems($this->class);

        return [$posts, $paginated];
    }
}
