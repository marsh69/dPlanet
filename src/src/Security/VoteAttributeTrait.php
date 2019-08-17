<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait VoteAttributeTrait
{
    /** @var array $permissions */
    protected $permissions;

    /**
     * @param $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        foreach ($this->permissions as $method => $attributes) {
            if (in_array($attribute, $attributes) && $this->$method($subject, $token)) {
                return true;
            }
        }

        return false;
    }
}