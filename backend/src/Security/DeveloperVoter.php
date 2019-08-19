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
    const ATTRIBUTES = [self::LIST, self::EDIT, self::DELETE, self::SHOW];

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
            'isOwner' => [self::SHOW],
            'isAdmin' => [self::DELETE, self::EDIT, self::LIST, self::SHOW],
            'isModerator' => [self::SHOW],
        ];
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

    /**
     * {@inheritDoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Comment && in_array($attribute, self::ATTRIBUTES);
    }
}
