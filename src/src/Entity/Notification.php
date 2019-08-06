<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class Notification
{
    use IdTrait;
    use TimestampableEntity;

    /** @var Developer|null $sender */
    protected $sender;
    /** @var Developer|null $receiver */
    protected $receiver;
    /** @var string $message */
    protected $message = '';
    /** @var bool $viewed */
    protected $viewed = false;
    /** @var bool $opened */
    protected $opened = false;

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
        return $this->viewed;
    }

    /**
     * @param bool $viewed
     * @return Notification
     */
    public function setViewed(bool $viewed): Notification
    {
        $this->viewed = $viewed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOpened(): bool
    {
        return $this->opened;
    }

    /**
     * @param bool $opened
     * @return Notification
     */
    public function setOpened(bool $opened): Notification
    {
        $this->opened = $opened;
        return $this;
    }
}