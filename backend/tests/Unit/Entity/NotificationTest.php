<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Notification
 * @covers \App\Entity\Message
 */
class NotificationTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Notification::class;
}
