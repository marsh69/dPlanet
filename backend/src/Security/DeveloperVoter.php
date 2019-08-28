<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\Developer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeveloperVoter extends Voter
{
    use VoteAttributeTrait;

    const LIST = 'list';
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const LIST_LIKES = 'list_likes';
    const LIST_NOTIFICATIONS = 'list_notifications';
    const ATTRIBUTES = [self::LIST, self::EDIT, self::DELETE, self::SHOW, self::LIST_LIKES, self::LIST_NOTIFICATIONS];

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;

    /**
     * CommentVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(
        AccessDecisionManagerInterface $decisionManager
    ) {
        $this->decisionManager = $decisionManager;

        $this->permissions = [
            'isOwner' => [self::SHOW, self::LIST_LIKES, self::LIST_NOTIFICATIONS],
            'isAdmin' => [self::DELETE, self::EDIT, self::LIST, self::SHOW, self::LIST_LIKES, self::LIST_NOTIFICATIONS],
            'isModerator' => [self::SHOW],
        ];
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Developer && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param Developer $developer
     * @param TokenInterface $token
     * @return bool
     */
    protected function isOwner(Developer $developer, TokenInterface $token): bool
    {
        return $developer === $token->getUser();
    }
}
