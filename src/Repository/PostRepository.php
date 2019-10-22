<?php

namespace App\Repository;

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
        if($post === false) {
            throw new Exception("Article introuvable");
        }
        (new CategoryRepository())->hydratePostsWithCategories([$post]);
        (new CommentRepository())->hydratePostWithComments($post);
        (new UserRepository())->hydratePostWithUser($post);
        return $post;
    }

    public function findPostByCategory(int $id)
    {
        $query = self::getDb()->prepare("
            SELECT * FROM {$this->repository} 
            JOIN post_category pc ON pc.post_id = {$this->repository}.id 
            WHERE pc.category_id = :id ORDER BY created_at DESC
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
}
