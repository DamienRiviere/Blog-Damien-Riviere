<?php

namespace App\Repository;

use App\Model\Like;

class LikeRepository extends Repository
{

    protected $repository = "post_like";

    protected $class = Like::class;

    public function createLike(Like $like): void
    {
        $this->create([
            'post_id' => $like->getPostId(),
            'user_id' => $like->getUserId()
        ]);
    }

    /**
     * Find if the user connected has liked the post
     *
     * @param int $postId
     * @return mixed|null
     */
    public function findLike(int $postId)
    {
        if ($_SESSION != null) {
            $query = self::getDb()->prepare('
				SELECT post_id, user_id 
				FROM post_like 
				WHERE post_id = :post_id 
				AND user_id = :user_id
			');
            $query->execute([
                ':post_id' => $postId,
                ':user_id' => $_SESSION['id']
            ]);
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
            $like = $query->fetch();

            return $like;
        }

        return $like = null;
    }

    public function deleteLike(int $postId)
    {
        $query = self::getDb()->prepare('
			DELETE FROM post_like 
			WHERE post_id = :post_id 
			AND user_id = :user_id
		');
        $query->execute([
            ':post_id' => $postId,
            ':user_id' => $_SESSION['id']
        ]);
    }
}
