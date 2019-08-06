<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Post
{
    use IdTrait;
    use TimestampableEntity;

    /** @var string $body */
    protected $body = '';
    /** @var Developer|null $postedBy */
    protected $postedBy;
    /** @var bool $isDeleted */
    protected $isDeleted = false;
    /** @var bool $isClosedByUser */
    protected $isClosedByUser = false;
    /** @var Collection|Like[] $likes */
    protected $likes;
    /** @var Image|null $image */
    protected $image;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Post
     */
    public function setBody(string $body): Post
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return Developer|null
     */
    public function getPostedBy(): ?Developer
    {
        return $this->postedBy;
    }

    /**
     * @param Developer|null $postedBy
     * @return Post
     */
    public function setPostedBy(?Developer $postedBy): Post
    {
        $this->postedBy = $postedBy;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return Post
     */
    public function setIsDeleted(bool $isDeleted): Post
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return bool
     */
    public function isClosedByUser(): bool
    {
        return $this->isClosedByUser;
    }

    /**
     * @param bool $isClosedByUser
     * @return Post
     */
    public function setIsClosedByUser(bool $isClosedByUser): Post
    {
        $this->isClosedByUser = $isClosedByUser;
        return $this;
    }

    /**
     * @return Like[]|Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param Like[]|Collection $likes
     * @return Post
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @return Image|null
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image|null $image
     * @return Post
     */
    public function setImage(?Image $image): Post
    {
        $this->image = $image;
        return $this;
    }
}