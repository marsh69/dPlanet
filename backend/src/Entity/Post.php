<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Post
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var string $body */
    protected $body = '';
    /** @var Developer|null $postedBy */
    protected $postedBy;
    /** @var bool $isDeleted */
    protected $isDeleted = false;
    /** @var bool $isClosedByUser */
    protected $isClosedByUser = false;
    /** @var Collection|Comment[] $comments */
    protected $comments;
    /** @var Collection|Like[] $likes */
    protected $likes;
    /** @var Collection|Trend[] $trends */
    protected $trends;
    /** @var Image|null $image */
    protected $image;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->trends = new ArrayCollection();
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
     * @return Like[]
     */
    public function getLikes(): array
    {
        return $this->likes->toArray();
    }

    /**
     * @param Like[] $likes
     * @return Post
     */
    public function setLikes($likes): Post
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

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments->toArray();
    }

    /**
     * @param Comment[] $comments
     * @return Post
     */
    public function setComments($comments): Post
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return Trend[]
     */
    public function getTrends(): array
    {
        return $this->trends->toArray();
    }

    /**
     * @param Trend[] $trends
     * @return Post
     */
    public function setTrends($trends): Post
    {
        $this->trends = $trends;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmountOfComments(): int
    {
        return count($this->comments);
    }

    /**
     * @return int
     */
    public function getAmountOfLikes(): int
    {
        return count($this->likes);
    }

    /**
     * @return int
     */
    public function getAmountOfTrends(): int
    {
        return count($this->trends);
    }
}
