<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Trend
{
    use IdTrait;
    use TimestampableEntity;
    use IsDeletedTrait;

    /** @var string $name */
    protected $name = '';
    /** @var Post[]|Collection $posts */
    protected $posts;

    /**
     * Trend constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Trend
     */
    public function setName(string $name): Trend
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Post[]|Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param Post[]|Collection $posts
     * @return Trend
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * @return int
     */
    public function getHits(): int
    {
        return count($this->posts);
    }
}
