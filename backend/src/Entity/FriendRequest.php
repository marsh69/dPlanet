<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class FriendRequest
{
    use IdTrait;
    use IsDeletedTrait;
    use TimestampableEntity;

    /** @var Developer|null $sender */
    protected $sender;
    /** @var Developer|null $receiver */
    protected $receiver;

    /**
     * @return Developer|null
     */
    public function getSender(): ?Developer
    {
        return $this->sender;
    }

    /**
     * @param Developer|null $sender
     * @return FriendRequest
     */
    public function setSender(?Developer $sender): FriendRequest
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
     * @return FriendRequest
     */
    public function setReceiver(?Developer $receiver): FriendRequest
    {
        $this->receiver = $receiver;
        return $this;
    }
}
