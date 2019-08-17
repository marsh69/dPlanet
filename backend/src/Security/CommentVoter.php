<?php

namespace App\Security;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    use VoteAttributeTrait;

    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;
    /** @var int $allowedSecondsToEdit */
    protected $allowedSecondsToEdit;
    /** @var int $allowedSecondsToDelete */
    protected $allowedSecondsToDelete;

    /**
     * CommentVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     * @param int $allowedSecondsToEdit
     * @param int $allowedSecondsToDelete
     */
    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        int $allowedSecondsToEdit,
        int $allowedSecondsToDelete
    ) {
        $this->decisionManager = $decisionManager;
        $this->allowedSecondsToEdit = $allowedSecondsToEdit;
        $this->allowedSecondsToDelete = $allowedSecondsToDelete;

        $this->permissions = [
            'isOwner' => [self::DELETE, self::EDIT, self::CREATE],
            'isAdmin' => [self::DELETE, self::EDIT, self::CREATE]
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function supports($attribute, $subject): bool
    {
        $attributes = [
            self::CREATE,
            self::DELETE,
            self::EDIT
        ];

        return $subject instanceof Comment && in_array($attribute, $attributes);
    }

    /**
     * @param Comment $comment
     * @param TokenInterface $token
     * @return bool
     */
    protected function isOwner(Comment $comment, TokenInterface $token): bool
    {
        // TODO: Ensure old comment can't be edited
        return $this->decisionManager->decide($token, []);
    }

    /**
     * @param Comment $comment
     * @param TokenInterface $token
     * @return bool
     */
    protected function isAdmin(Comment $comment, TokenInterface $token): bool
    {
        return true;
    }
}
