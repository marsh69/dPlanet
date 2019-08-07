<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Developer extends User
{
    use TimestampableEntity;

    /** @var string $firstName */
    protected $firstName = '';
    /** @var string $lastName */
    protected $lastName = '';
    /** @var Image|null $profileImage */
    protected $profileImage;
    /** @var Notification[]|Collection $receivedNotifications */
    protected $receivedNotifications;
    /** @var Message[]|Collection $sentMessages */
    protected $sentMessages;
    /** @var Message[]|Collection $receivedMessages */
    protected $receivedMessages;
    /** @var Like[]|Collection $likes */
    protected $likes;
    /** @var Comment[]|Collection $comments */
    protected $comments;
    /** @var Notification[]|Collection $sentNotifications */
    protected $sentNotifications;
    /** @var Post[]|Collection $posts */
    protected $posts;
    /** @var FriendRequest[]|Collection $sentFriendRequests */
    protected $sentFriendRequests;
    /** @var FriendRequest[]|Collection $receivedFriendRequests */
    protected $receivedFriendRequests;

    /**
     * Developer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->receivedNotifications = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();
    }

    /**
     * @return Image|null
     */
    public function getProfileImage(): ?Image
    {
        return $this->profileImage;
    }

    /**
     * @param Image|null $profileImage
     * @return Developer
     */
    public function setProfileImage(?Image $profileImage): Developer
    {
        $this->profileImage = $profileImage;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Developer
     */
    public function setFirstName(string $firstName): Developer
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Developer
     */
    public function setLastName(string $lastName): Developer
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return Notification[]|Collection
     */
    public function getReceivedNotifications()
    {
        return $this->receivedNotifications;
    }

    /**
     * @param Notification[]|Collection $receivedNotifications
     * @return Developer
     */
    public function setReceivedNotifications($receivedNotifications)
    {
        $this->receivedNotifications = $receivedNotifications;
        return $this;
    }

    /**
     * @return Message[]|Collection
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * @param Message[]|Collection $sentMessages
     * @return Developer
     */
    public function setSentMessages($sentMessages)
    {
        $this->sentMessages = $sentMessages;
        return $this;
    }

    /**
     * @return Message[]|Collection
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * @param Message[]|Collection $receivedMessages
     * @return Developer
     */
    public function setReceivedMessages($receivedMessages)
    {
        $this->receivedMessages = $receivedMessages;
        return $this;
    }

    /**
     * @return Like[]|Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param Like[]|Collection $likes
     * @return Developer
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @return Comment[]|Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment[]|Collection $comments
     * @return Developer
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return Notification[]|Collection
     */
    public function getSentNotifications()
    {
        return $this->sentNotifications;
    }

    /**
     * @param Notification[]|Collection $sentNotifications
     * @return Developer
     */
    public function setSentNotifications($sentNotifications)
    {
        $this->sentNotifications = $sentNotifications;
        return $this;
    }

    /**
     * @return Post[]|Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param Post[]|Collection $posts
     * @return Developer
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * @return FriendRequest[]|Collection
     */
    public function getSentFriendRequests()
    {
        return $this->sentFriendRequests;
    }

    /**
     * @param FriendRequest[]|Collection $sentFriendRequests
     * @return Developer
     */
    public function setSentFriendRequests($sentFriendRequests)
    {
        $this->sentFriendRequests = $sentFriendRequests;
        return $this;
    }

    /**
     * @return FriendRequest[]|Collection
     */
    public function getReceivedFriendRequests()
    {
        return $this->receivedFriendRequests;
    }

    /**
     * @param FriendRequest[]|Collection $receivedFriendRequests
     * @return Developer
     */
    public function setReceivedFriendRequests($receivedFriendRequests)
    {
        $this->receivedFriendRequests = $receivedFriendRequests;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmountOfPosts(): int
    {
        return count($this->posts);
    }

    /**
     * @return int
     */
    public function getAmountOfComments(): int
    {
        return count($this->comments);
    }

    /**
     * @return int
     */
    public function getAmountOfLikes(): int
    {
        return count($this->likes);
    }

    /**
     * @return int
     */
    public function getAmountOfSentMessages(): int
    {
        return count($this->sentMessages);
    }

    /**
     * @return int
     */
    public function getAmountOfReceivedMessages(): int
    {
        return count($this->receivedFriendRequests);
    }
}
