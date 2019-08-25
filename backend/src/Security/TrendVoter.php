<?php

namespace App\Security;

use App\Entity\Trend;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TrendVoter extends Voter
{
    use VoteAttributeTrait;

    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ATTRIBUTES = [self::CREATE, self::EDIT, self::DELETE];

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;

    /**
     * TrendVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(
        AccessDecisionManagerInterface $decisionManager
    ) {
        $this->decisionManager = $decisionManager;

        $this->permissions = [
            'isAdmin' => [self::DELETE, self::EDIT, self::CREATE],
        ];
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Trend && in_array($attribute, self::ATTRIBUTES);
    }
}
