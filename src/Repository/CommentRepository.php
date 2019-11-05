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

        if (empty($comments)) {
            throw new \Exception("Ce statut de commentaire n'existe pas !");
        }

        $commentsWithUser = [];

        foreach ($comments as $comment) {
            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);
            $commentsWithUser[] = $comment;
        }

        return [$commentsWithUser, $paginated];
    }

    public function findCountCommentsByUser($id)
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
     * Find the total number of comments publicated for dashboard
     *
     * @return mixed
     */
    public function findCountCommentsNotPublicated()
    {
        $query = self::getDb()->prepare("
			SELECT COUNT(id)
			FROM {$this->repository}
			WHERE status_id = 1
		");
        $query->execute();
        $count = $query->fetch();
        return $count[0];
    }

    /**
     * Find the total number of comments reported for dashboard
     *
     * @return mixed
     */
    public function findCountCommentsReported()
    {
        $query = self::getDb()->prepare("
			SELECT COUNT(id)
			FROM {$this->repository}
			WHERE status_id = 3
		");
        $query->execute();
        $count = $query->fetch();
        return $count[0];
    }

    /**
     * Find the total number of comments moderated for dashboard
     *
     * @return mixed
     */
    public function findCountCommentsModerated()
    {
        $query = self::getDb()->prepare("
			SELECT COUNT(id)
			FROM {$this->repository}
			WHERE status_id = 4
		");
        $query->execute();
        $count = $query->fetch();
        return $count[0];
    }

    /**
     * Find the last five comments reported for dashboard
     *
     * @return array
     * @throws \Exception
     */
    public function findLastFiveCommentsReported()
    {
        $query = self::getDb()->prepare("
			SELECT * FROM {$this->repository} WHERE status_id = 3 ORDER BY created_at DESC LIMIT 5
		");
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $query->execute();
        $lastFiveCommentsReported = $query->fetchAll();

        $lastComments = [];

        foreach ($lastFiveCommentsReported as $comment) {
            // Hydrate a comment with his user
            (new UserRepository())->hydrateCommentWithUser($comment);

            $lastComments[] = $comment;
        }

        return $lastComments;
    }
}
