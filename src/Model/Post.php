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

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = htmlspecialchars($title);

        return $this;
    }

    /**
     * Get the value of introduction
     */
    public function getIntroduction(): string
    {
        return $this->introduction;
    }

    /**
     * Set the value of introduction
     *
     * @return self
     */
    public function setIntroduction(string $introduction): self
    {
        $this->introduction = htmlspecialchars($introduction);

        return $this;
    }

    public function getExcerpt(): ?string
    {
        if ($this->introduction === null) {
            return null;
        }
        return nl2br(htmlentities(Text::excerpt($this->introduction, 400)));
    }

    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = htmlspecialchars($content);

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of modifyAt
     */
    public function getModifyAt(): DateTime
    {
        return $this->modifyAt;
    }

    /**
     * Set the value of modifyAt
     *
     * @return self
     */
    public function setModifyAt(DateTime $modifyAt)
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }

    /**
     * Get the value of coverImage
     */
    public function getCoverImage(): string
    {
        return $this->coverImage;
    }

    /**
     * Set the value of coverImage
     *
     * @return self
     */
    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
