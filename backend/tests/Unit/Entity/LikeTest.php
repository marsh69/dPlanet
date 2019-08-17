<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Like;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Like
 * @covers \App\Entity\Message
 */
class LikeTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Like::class;
}
