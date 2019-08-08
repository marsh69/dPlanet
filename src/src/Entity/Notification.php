<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class Notification
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var Developer|null $sender */
    protected $sender;
    /** @var Developer|null $receiver */
    protected $receiver;
    /** @var string $message */
    protected $message = '';
    /** @var bool $isViewed */
    protected $isViewed = false;
    /** @var bool $isOpened */
    protected $isOpened = false;

    /**
     * @return Developer|null
     */
    public function getSender(): ?Developer
    {
        return $this->sender;
    }

    /**
     * @param Developer|null $sender
     * @return Notification
     */
    public function setSender(?Developer $sender): Notification
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return Developer|null
     */
    public function getReceiver(): ?Developer
    {
        return $this->receiver;
    }

    /**
     * @param Developer|null $receiver
     * @return Notification
     */
    public function setReceiver(?Developer $receiver): Notification
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Notification
     */
    public function setMessage(string $message): Notification
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function isViewed(): bool
    {
        return $this->isViewed;
    }

    /**
     * @param bool $isViewed
     * @return Notification
     */
    public function setIsViewed(bool $isViewed): Notification
    {
        $this->isViewed = $isViewed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOpened(): bool
    {
        return $this->isOpened;
    }

    /**
     * @param bool $isOpened
     * @return Notification
     */
    public function setIsOpened(bool $isOpened): Notification
    {
        $this->isOpened = $isOpened;
        return $this;
    }
}