<?php

namespace App\Security;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    use VoteAttributeTrait;

    const LIST_LIKES = 'list_likes';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ATTRIBUTES = [self::EDIT, self::DELETE, self::LIST_LIKES];

    /** @var AccessDecisionManagerInterface $decisionManager */
    protected $decisionManager;
    /** @var int $allowedSecondsToEdit */
    protected $allowedSecondsToEdit;

    /**
     * PostVoter constructor.
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
            'isAdmin' => [self::DELETE, self::EDIT, self::LIST_LIKES],
            'isModerator' => [self::EDIT],
        ];
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Post && in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param Post $post
     * @param TokenInterface $token
     * @return bool
     * @throws \Exception
     */
    protected function isOwner(Post $post, TokenInterface $token): bool
    {
        $secondsPassed = (new \DateTime())->getTimestamp() - $post->getCreatedAt()->getTimestamp();
        return $post->getPostedBy() === $token->getUser() && $secondsPassed <= $this->allowedSecondsToEdit;
    }
}
