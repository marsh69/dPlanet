<?php

namespace App\Entity;

trait IdTrait
{
    /** @var string $id */
    protected $id = '';

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return IdTrait
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }
}
