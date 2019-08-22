<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use App\Entity\Developer;
use App\Entity\FriendRequest;
use App\Entity\Like;
use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Developer
 * @covers \App\Entity\Developer
 */
class DeveloperTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Developer::class;

    /** @var array $excludedGetters */
    protected $excludedGetters = [
        'getRoles',
        'getPosts',
        'getReceivedNotifications',
        'getSentNotifications',
        'getSentFriendRequests',
        'getReceivedFriendRequests',
        'getLikes',
        'getComments',
        'getSentMessages',
        'getReceivedMessages'
    ];

    /**
     * TODO: Separate these
     *
     * @covers ::getLikes
     * @covers ::getReceivedMessages
     * @covers ::getReceivedNotifications
     * @covers ::getPosts
     * @covers ::getSentFriendRequests
     * @covers ::getComments
     * @covers ::getReceivedFriendRequests
     */
    public function testIfRemainingSettersWork(): void
    {
        $developer = new Developer();

        $developer
            ->setReceivedFriendRequests(new ArrayCollection([new Notification(), new Notification()]))
            ->setSentMessages(new ArrayCollection([new Message(), new Message(), new Message()]))
            ->setLikes(new ArrayCollection([new Like(), new Like(), new Like(), new Like(), new Like()]))
            ->setPosts(new ArrayCollection([new Post(), new Post(), new Post(), new Post(), new Post(), new Post()]))
            ->setSentFriendRequests(new ArrayCollection([new FriendRequest()]));

        $this->assertCount(2, $developer->getReceivedFriendRequests());
        $this->assertCount(3, $developer->getSentMessages());
        $this->assertCount(5, $developer->getLikes());
        $this->assertCount(6, $developer->getPosts());
        $this->assertCount(1, $developer->getSentFriendRequests());
    }

    /**
     * @covers ::setSentNotifications
     * @covers ::getSentNotifications
     */
    public function testIfSentNotificationsSetterWorks(): void
    {
        $developer = new Developer();

        $developer
            ->setSentNotifications(new ArrayCollection([new Notification(), new Notification()]));

        $this->assertCount(2, $developer->getSentNotifications());
    }

    /**
     * @covers ::setReceivedNotifications
     * @covers ::getReceivedNotifications
     */
    public function testIfReceivedNotificationsSetterWorks(): void
    {
        $developer = new Developer();

        $developer
            ->setReceivedNotifications(new ArrayCollection([new Notification(), new Notification()]));

        $this->assertCount(2, $developer->getReceivedNotifications());
    }

    /**
     * @covers ::setReceivedMessages
     * @covers ::getReceivedMessages
     */
    public function testIfReceivedMessagesSetterWorks(): void
    {
        $developer = new Developer();

        $developer
            ->setReceivedMessages(new ArrayCollection([new Message(), new Message()]));

        $this->assertCount(2, $developer->getReceivedMessages());
    }

    /**
     * @covers ::getComments
     * @covers ::setComments
     */
    public function testIfGetCommentsWorks(): void
    {
        $developer = new Developer();

        $developer
            ->setComments(new ArrayCollection([new Comment(), new Comment(), new Comment()]));

        $this->assertCount(3, $developer->getComments());
    }

    /**
     * @covers ::getAmountOfPosts
     * @covers ::setPosts
     */
    public function testIfGetAmountOfPostsWorks(): void
    {
        $developer = new Developer();

        $developer->setPosts([
            new Post(),
            new Post(),
            new Post()
        ]);

        $this->assertEquals(3, $developer->getAmountOfPosts());
    }

    /**
     * @covers ::getAmountOfComments
     * @covers ::setComments
     */
    public function testIfGetAmountOfCommentsWorks(): void
    {
        $developer = new Developer();

        $developer->setComments([
            new Comment(),
            new Comment(),
            new Comment()
        ]);

        $this->assertEquals(3, $developer->getAmountOfComments());
    }

    /**
     * @covers ::getAmountOfLikes
     * @covers ::setLikes
     */
    public function testIfGetAmountOfLikesWorks(): void
    {
        $developer = new Developer();

        $developer->setLikes([
            new Like(),
            new Like(),
            new Like()
        ]);

        $this->assertEquals(3, $developer->getAmountOfLikes());
    }

    /**
     * @covers ::getAmountOfReceivedNotifications
     * @covers ::setReceivedNotifications
     */
    public function testIfGetAmountOfReceivedNotificationsWorks(): void
    {
        $developer = new Developer();

        $developer->setReceivedNotifications([
            new Notification(),
            new Notification(),
            new Notification()
        ]);

        $this->assertEquals(3, $developer->getAmountOfReceivedNotifications());
    }

    /**
     * @covers ::getAmountOfReceivedMessages
     * @covers ::setReceivedMessages
     */
    public function testIfGetAmountOfReceivedMessagesWorks(): void
    {
        $developer = new Developer();

        $developer->setReceivedMessages([
            new Message(),
            new Message(),
            new Message()
        ]);

        $this->assertEquals(3, $developer->getAmountOfReceivedMessages());
    }

    /**
     * @covers ::getAmountOfSentMessages
     * @covers ::setSentMessages
     */
    public function testIfGetAmountOfSentMessagesWorks(): void
    {
        $developer = new Developer();

        $developer->setSentMessages([
            new Message(),
            new Message(),
            new Message()
        ]);

        $this->assertEquals(3, $developer->getAmountOfSentMessages());
    }

    /**
     * @covers ::getFullName
     */
    public function testIfFullNameWorks(): void
    {
        $developer = new Developer();

        $developer->setFirstName('Bob')
            ->setLastName('Johnson');

        $this->assertEquals('Bob Johnson', $developer->getFullName());
    }
}
