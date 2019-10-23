<?php

namespace App\Repository;

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
            'created_at' => $comment->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Hydrate a post with his comments
     *
     * @param Post $post
     * @return void
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
     * Find all comments of a post
     *
     * @param integer $id
     * @return void
     */
    public function findComments(int $id)
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
}
