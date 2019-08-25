<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Notification
 * @covers \App\Entity\Notification
 */
class NotificationTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Notification::class;

    /**
     * @covers ::isOpened
     * @covers ::setIsOpened
     */
    public function testIfIsOpenedWorks(): void
    {
        $notification = new Notification();

        $notification->setIsOpened(false);
        $this->assertFalse($notification->isOpened());

        $notification->setIsOpened(true);
        $this->assertTrue($notification->isOpened());
    }

    /**
     * @covers ::isViewed
     * @covers ::setIsViewed
     */
    public function testIfIsViewedWorks(): void
    {
        $notification = new Notification();

        $notification->setIsViewed(false);
        $this->assertFalse($notification->isViewed());

        $notification->setIsViewed(true);
        $this->assertTrue($notification->isViewed());
    }
}
