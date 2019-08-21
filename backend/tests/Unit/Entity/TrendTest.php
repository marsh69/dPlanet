<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Post;
use App\Entity\Trend;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Trend
 * @covers ::__construct
 * @covers ::setName
 * @covers ::getName
 */
class TrendTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Trend::class;

    /** @var array $excludedGetters */
    protected $excludedGetters = ['getPosts'];

    /**
     * @covers ::getHits
     */
    public function testIfHitsWorksProperly(): void
    {
        $trend = new Trend();

        $trend
            ->setPosts(new ArrayCollection([new Post(), new Post()]));

        $this->assertEquals(2, $trend->getHits());
    }

    /**
     * @covers ::getHits
     */
    public function testIfHitsReturnsZeroWithNoPosts(): void
    {
        $this->assertEquals(0, (new Trend())->getHits());
    }

    /**
     * @covers ::getPosts
     * @covers ::setPosts
     */
    public function testIfGetPostsWorksProperly(): void
    {
        $trend = new Trend();

        $trend
            ->setPosts(new ArrayCollection([new Post(), new Post(), new Post()]));

        $this->assertCount(3, $trend->getPosts());
    }
}
