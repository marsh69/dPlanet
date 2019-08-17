<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class Message
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var Developer $sender */
    protected $sender;
    /** @var Developer $receiver */
    protected $receiver;
    /** @var string $body */
    protected $body = '';
    /** @var bool $isOpened */
    protected $isOpened = false;

    /**
     * @return Developer
     */
    public function getSender(): Developer
    {
        return $this->sender;
    }

    /**
     * @param Developer $sender
     * @return Message
     */
    public function setSender(Developer $sender): Message
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return Developer
     */
    public function getReceiver(): Developer
    {
        return $this->receiver;
    }

    /**
     * @param Developer $receiver
     * @return Message
     */
    public function setReceiver(Developer $receiver): Message
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): Message
    {
        $this->body = $body;
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
     * @return Message
     */
    public function setIsOpened(bool $isOpened): Message
    {
        $this->isOpened = $isOpened;
        return $this;
    }
}
