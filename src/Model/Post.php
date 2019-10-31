<?php

namespace App\Model;

use App\Helpers\Text;

class Post
{

    private $id;

    private $title;

    private $introduction;

    private $content;

    private $created_at;

    private $modify_at;

    private $coverImage;

    private $slug;

    private $categories = [];

    private $comments;

    private $user_id;

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

    public function getTitle(): string
    {
        return strip_tags(html_entity_decode($this->title));
    }

    public function setTitle(string $title): self
    {
        $this->title = htmlspecialchars($title);

        return $this;
    }

    public function getIntroduction(): string
    {
        return strip_tags(html_entity_decode($this->introduction));
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = htmlspecialchars($introduction);

        return $this;
    }

    public function getContent(): string
    {
        return strip_tags(html_entity_decode($this->content));
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

    public function getCoverImage(): string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
        
        $category->setPost($this);
    }

    /**
     * @return Comment[]
     */
    public function getComments(): ?array
    {
        return $this->comments;
    }

    /**
     * Add a comment in his post
     *
     * @param Comment $comment
     * @return void
     */
    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;

        $comment->setPost($this);
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function addUser(User $user): void
    {
        $this->user = $user;

        $user->setPost($this);
    }
}
