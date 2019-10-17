<?php

namespace App\Model;

use App\Helpers\Text;

class Post
{

    private $id;

    private $title;

    private $introduction;

    private $content;

    private $createdAt;

    private $modifyAt;

    private $coverImage;

    private $slug;

    private $categories = [];

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
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = htmlspecialchars($title);

        return $this;
    }

    public function getIntroduction(): string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = htmlspecialchars($introduction);

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
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifyAt()
    {
        return $this->modifyAt;
    }

    public function setModifyAt($modifyAt)
    {
        $this->modifyAt = $modifyAt;

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
}
