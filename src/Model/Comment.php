<?php

namespace App\Model;

class Comment {

    private $id;

    private $post_id;

    private $user_id;

    private $content;

    private $created_at;

    private $modify_at;

    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): self
    {
        $this->post_id = $post_id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
 
    public function setContent(string $content): self
    {
        $this->content = htmlspecialchars($content);

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;

        return $this;
    }

    public function setPost(Post $post)
    {
        $this->post = $post;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Add a user in his comment
     *
     * @param User $user
     * @return void
     */
    public function addUser(User $user): void
    {
        $this->user = $user;

        $user->setComment($this);
    }
}