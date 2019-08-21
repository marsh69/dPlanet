<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Post;
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
}
