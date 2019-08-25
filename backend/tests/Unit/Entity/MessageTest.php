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

    /**
     * @covers ::isOpened
     * @covers ::setIsOpened
     */
    public function testIfOpenedWorks(): void
    {
        $message = new Message();

        $message->setIsOpened(true);
        $this->assertTrue($message->isOpened());

        $message->setIsOpened(false);
        $this->assertFalse($message->isOpened());
    }
}
