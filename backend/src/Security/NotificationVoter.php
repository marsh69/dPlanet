<?php

namespace App\Security;

use App\Entity\Notification;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class NotificationVoter extends Voter
{
    use VoteAttributeTrait;

    const LIST = 'list';
    const LIST_DEVELOPER = 'list_developer';
    const SHOW = 'show';
    const DELETE = 'delete';
    const ATTRIBUTES = [self::LIST, self::DELETE, self::LIST_DEVELOPER, self::SHOW];

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;

    /**
     * NotificationVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(
        AccessDecisionManagerInterface $decisionManager
    ) {
        $this->decisionManager = $decisionManager;

        $this->permissions = [
            'isOwner' => [self::DELETE, self::LIST_DEVELOPER, self::SHOW],
            'isAdmin' => [self::DELETE, self::LIST, self::LIST_DEVELOPER, self::SHOW],
        ];
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Notification && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param Notification $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function isOwner($subject, TokenInterface $token): bool
    {
        return $subject->getReceiver() == $token->getUser();
    }
}
