<?php

namespace App\Tests\Unit\Entity;

use App\Entity\FriendRequest;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\FriendRequest
 * @covers \App\Entity\FriendRequest
 */
class FriendRequestTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = FriendRequest::class;
}
