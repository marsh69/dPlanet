<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Message
 * @covers \App\Entity\Message
 */
class MessageTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Message::class;
}
