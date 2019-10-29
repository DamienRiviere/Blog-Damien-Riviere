<?php

namespace App\Repository;

use App\Services\Pagination;
use PDO;
use App\Model\Post;
use App\Model\Comment;

class CommentRepository extends Repository
{

    protected $repository = "comment";

    protected $class = Comment::class;

    public function createComment(Comment $comment): void
    {
        $comment = $this->create([
            'content' => $comment->getContent(),
            'post_id' => $comment->getPostId(),
            'user_id' => $comment->getUserId(),
            'created_at' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
            'status_id' => $comment->getStatusId()
        ]);
    }

    public function updateComment(Comment $comment, int $id): void
    {
        $comment = $this->update([
            'content' => $comment->getContent(),
            'modify_at' => $comment->getModifyAt()->format('Y-m-d H:i:s')
        ], $id);
    }

    public function updateStatus(Comment $comment, int $id): void
    {
        $comment = $this->update([
            'status_id' => $comment->getStatusId()
        ], $id);
    }

    /**
     * Find all comments of a post
     *
     * @param integer $id
     * @return array
     * @throws \Exception
     */
    public function findComments(int $id): array
    {
        $query = self::getDb()->prepare("SELECT * FROM {$this->repository} WHERE post_id = ?");
        $query->execute([$id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $comments = $query->fetchAll();

        $commentsWithUser = [];

        foreach ($comments as $comment) {
            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);

            $commentsWithUser[] = $comment;
        }

        return $commentsWithUser;
    }

    /**
     * Find all comments by status
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findCommentsByStatus(int $id): array
    {
        $query = self::getDb()->prepare("
			SELECT * FROM {$this->repository} 
			WHERE status_id = ? 
			ORDER BY created_at DESC");
        $query->execute([$id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $comments = $query->fetchAll();

        $commentsWithUser = [];

        foreach ($comments as $comment) {
            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);
            $commentsWithUser[] = $comment;
        }

        return $commentsWithUser;
    }

    /**
     * Hydrate a post with his comments
     *
     * @param Post $post
     * @return void
     * @throws \Exception
     */
    public function hydratePostWithComments(Post $post): void
    {
        $query = self::getDb()->prepare('
            SELECT c.*
            FROM comment c
            JOIN post p ON c.post_id = p.id
            WHERE p.id = ?
        ');
        $query->execute([$post->getId()]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $comments = $query->fetchAll();

        // Add all post in with his comments
        foreach ($comments as $comment) {
            $post->addComment($comment);

            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);
        }
    }

    /**
     * Find comments by post with pagination
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findCommentsPaginated(int $id): array
    {
        $paginated = new Pagination(
            "SELECT * FROM {$this->repository} WHERE post_id = ? AND status_id BETWEEN 2 AND 3 
			ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->repository} WHERE post_id = ? AND status_id BETWEEN 2 AND 3",
            10,
            $id
        );
        $comments = $paginated->getItems($this->class);

        $commentsWithUser = [];

        foreach ($comments as $comment) {
            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);

            $commentsWithUser[] = $comment;
        }

        return [$commentsWithUser, $paginated];
    }

    /**
     * Find Comments by status with pagination
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findCommentsByStatusPaginated(int $id): array
    {
        $paginated = new Pagination(
            "SELECT * FROM {$this->repository} WHERE status_id = ? ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->repository} WHERE status_id = ?",
            10,
            $id
        );
        $comments = $paginated->getItems($this->class);

        $commentsWithUser = [];

        foreach ($comments as $comment) {
            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);
            $commentsWithUser[] = $comment;
        }

        return [$commentsWithUser, $paginated];
    }
}
