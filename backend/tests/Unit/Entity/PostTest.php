<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\Trend;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Post
 * @covers \App\Entity\Post
 */
class PostTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Post::class;

    protected $excludedGetters = ['getComments', 'getTrends', 'getLikes'];

    /**
     * @covers ::getAmountOfComments
     */
    public function testIfGetAmountOfCommentsWorks(): void
    {
        $post = new Post();

        $post->setComments(new ArrayCollection([new Comment(), new Comment(), new Comment(), new Comment()]));

        $this->assertEquals(4, $post->getAmountOfComments());
    }

    /**
     * @covers ::getAmountOfTrends
     */
    public function testIfGetAmountOfTrendsWorks(): void
    {
        $post = new Post();

        $post->setTrends(new ArrayCollection([new Trend(), new Trend()]));

        $this->assertEquals(2, $post->getAmountOfTrends());
    }

    /**
     * @covers ::getAmountOfLikes
     */
    public function testIfGetAmountOfLikesWorks(): void
    {
        $post = new Post();

        $post->setLikes(new ArrayCollection([new Like(), new Like(), new Like()]));

        $this->assertEquals(3, $post->getAmountOfLikes());
    }

    /**
     * @covers ::isClosedByUser
     * @covers ::setIsClosedByUser
     */
    public function testIfClosedByUserWorks(): void
    {
        $post = new Post();

        $post->setIsClosedByUser(false);
        $this->assertFalse($post->isClosedByUser());

        $post->setIsClosedByUser(true);
        $this->assertTrue($post->isClosedByUser());
    }

    /**
     * @covers ::getComments
     * @covers ::setComments
     */
    public function testIfGetCommentsWorks(): void
    {
        $post = new Post();

        $post->setComments(new ArrayCollection([new Comment(), new Comment(), new Comment()]));

        $this->assertCount(3, $post->getComments());
    }

    /**
     * @covers ::getLikes
     * @covers ::setLikes
     */
    public function testIfGetLikesWorks(): void
    {
        $post = new Post();

        $post->setLikes(new ArrayCollection([new Like(), new Like(), new Like()]));

        $this->assertCount(3, $post->getLikes());
    }

    /**
     * @covers ::getTrends
     * @covers ::setTrends
     */
    public function testIfGetTrendsWorks(): void
    {
        $post = new Post();

        $post->setTrends(new ArrayCollection([new Trend(), new Trend(), new Trend()]));

        $this->assertCount(3, $post->getTrends());
    }
}
