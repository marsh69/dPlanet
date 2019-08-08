<?php

namespace App\Entity;

trait IsDeletedTrait
{
    /** @var bool $isDeleted */
    protected $isDeleted = false;

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return self
     */
    public function setIsDeleted(bool $isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
}
