<?php

namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /**
     * CommentService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Comment $comment
     */
    public function save(Comment $comment): void
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * @param Comment $comment
     */
    public function delete(Comment $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }
}
