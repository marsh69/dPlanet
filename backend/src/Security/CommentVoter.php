<?php

namespace App\Security;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    use VoteAttributeTrait;

    const EDIT = 'edit';
    const DELETE = 'delete';
    const ATTRIBUTES = [self::EDIT, self::DELETE];

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;
    /** @var int $allowedSecondsToEdit */
    protected $allowedSecondsToEdit;

    /**
     * CommentVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     * @param int $allowedSecondsToEdit
     */
    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        int $allowedSecondsToEdit
    ) {
        $this->decisionManager = $decisionManager;
        $this->allowedSecondsToEdit = $allowedSecondsToEdit;

        $this->permissions = [
            'isOwner' => [self::DELETE, self::EDIT],
            'isAdmin' => [self::DELETE, self::EDIT],
            'isModerator' => [self::DELETE, self::EDIT],
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Comment && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param Comment $comment
     * @param TokenInterface $token
     * @return bool
     * @throws \Exception
     */
    protected function isOwner(Comment $comment, TokenInterface $token): bool
    {
        $secondsPassed = (new \DateTime())->getTimestamp() - $comment->getCreatedAt()->getTimestamp();
        return $comment->getPostedBy() === $token->getUser() && $secondsPassed <= $this->allowedSecondsToEdit;
    }
}
