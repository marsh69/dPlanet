<?php

namespace App\Security;

use App\Entity\Like;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LikeVoter extends Voter
{
    use VoteAttributeTrait;

    const LIST = 'list';
    const DELETE = 'delete';
    const ATTRIBUTES = [self::LIST, self::DELETE];

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;

    /**
     * LikeVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(
        AccessDecisionManagerInterface $decisionManager
    ) {
        $this->decisionManager = $decisionManager;

        $this->permissions = [
            'isOwner' => [self::DELETE],
            'isAdmin' => [self::DELETE, self::LIST],
        ];
    }


    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Like && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param Like $like
     * @param TokenInterface $token
     * @return bool
     * @throws \Exception
     */
    protected function isOwner(Like $like, TokenInterface $token): bool
    {
        return $like->getDeveloper() === $token->getUser();
    }
}
