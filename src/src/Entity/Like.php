<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class Like
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var Developer|null $developer */
    protected $developer;
    /** @var Post|null $post */
    protected $post;

    /**
     * @return Developer
     */
    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    /**
     * @param Developer $developer
     * @return Like
     */
    public function setDeveloper(Developer $developer): Like
    {
        $this->developer = $developer;
        return $this;
    }

    /**
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return Like
     */
    public function setPost(Post $post): Like
    {
        $this->post = $post;
        return $this;
    }
}