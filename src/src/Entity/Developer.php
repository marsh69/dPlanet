<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User;

class Developer extends User
{
    /** @var string $firstName */
    protected $firstName = '';
    /** @var string $lastName */
    protected $lastName = '';
    /** @var Notification[]|Collection $notifications */
    protected $notifications;
    /** @var Message[]|Collection $sentMessages */
    protected $sentMessages;
    /** @var Message[]|Collection $receivedMessages */
    protected $receivedMessages;
    /** @var Like[]|Collection $likes */
    protected $likes;
    /** @var Comment[]|Collection $comments */
    protected $comments;

    /**
     * Developer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->notifications = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification[]|Collection $notifications
     * @return Developer
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
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
}