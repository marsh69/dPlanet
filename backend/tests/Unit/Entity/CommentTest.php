<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Comment
 * @covers \App\Entity\Comment
 */
class CommentTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Comment::class;
}
