<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class Comment
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var string $body */
    protected $body = '';
    /** @var Developer|null $postedBy */
    protected $postedBy;
    /** @var Post|null $postedTo */
    protected $postedTo;

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Comment
     */
    public function setBody(string $body): Comment
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
     * @return Comment
     */
    public function setPostedBy(?Developer $postedBy): Comment
    {
        $this->postedBy = $postedBy;
        return $this;
    }

    /**
     * @return Post|null
     */
    public function getPostedTo(): ?Post
    {
        return $this->postedTo;
    }

    /**
     * @param Post|null $postedTo
     * @return Comment
     */
    public function setPostedTo(?Post $postedTo): Comment
    {
        $this->postedTo = $postedTo;
        return $this;
    }
}
