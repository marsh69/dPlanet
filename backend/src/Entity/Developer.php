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

        $this->roles = ['ROLE_DEVELOPER'];
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
     * @return Notification[]
     */
    public function getReceivedNotifications(): array
    {
        return $this->receivedNotifications->toArray();
    }

    /**
     * @param Notification[] $receivedNotifications
     * @return Developer
     */
    public function setReceivedNotifications($receivedNotifications): self
    {
        $this->receivedNotifications = $receivedNotifications;
        return $this;
    }

    /**
     * @return Message[]
     */
    public function getSentMessages(): array
    {
        return $this->sentMessages->toArray();
    }

    /**
     * @param Message[] $sentMessages
     * @return Developer
     */
    public function setSentMessages($sentMessages): self
    {
        $this->sentMessages = $sentMessages;
        return $this;
    }

    /**
     * @return Message[]
     */
    public function getReceivedMessages(): array
    {
        return $this->receivedMessages->toArray();
    }

    /**
     * @param Message[] $receivedMessages
     * @return Developer
     */
    public function setReceivedMessages($receivedMessages): self
    {
        $this->receivedMessages = $receivedMessages;
        return $this;
    }

    /**
     * @return Like[]
     */
    public function getLikes(): array
    {
        return $this->likes->toArray();
    }

    /**
     * @param Like[] $likes
     * @return Developer
     */
    public function setLikes($likes): self
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments->toArray();
    }

    /**
     * @param Comment[] $comments
     * @return Developer
     */
    public function setComments($comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return Notification[]
     */
    public function getSentNotifications(): array
    {
        return $this->sentNotifications->toArray();
    }

    /**
     * @param Notification[] $sentNotifications
     * @return Developer
     */
    public function setSentNotifications($sentNotifications): self
    {
        $this->sentNotifications = $sentNotifications;
        return $this;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return $this->posts->toArray();
    }

    /**
     * @param Post[] $posts
     * @return Developer
     */
    public function setPosts($posts): self
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * @return FriendRequest[]
     */
    public function getSentFriendRequests(): array
    {
        return $this->sentFriendRequests->toArray();
    }

    /**
     * @param FriendRequest[] $sentFriendRequests
     * @return Developer
     */
    public function setSentFriendRequests($sentFriendRequests): self
    {
        $this->sentFriendRequests = $sentFriendRequests;
        return $this;
    }

    /**
     * @return FriendRequest[]
     */
    public function getReceivedFriendRequests(): array
    {
        return $this->receivedFriendRequests->toArray();
    }

    /**
     * @param FriendRequest[] $receivedFriendRequests
     * @return Developer
     */
    public function setReceivedFriendRequests($receivedFriendRequests): self
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

    /**
     * @return int
     */
    public function getAmountOfReceivedNotifications(): int
    {
        return count($this->receivedNotifications);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
