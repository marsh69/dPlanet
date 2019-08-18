<?php

namespace App\Service;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CommentService
{
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var EntityRepository $commentRepository */
    protected $commentRepository;

    /**
     * CommentService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->commentRepository = $em->getRepository(Comment::class);
    }

    /**
     * @return Comment[]
     */
    public function findAll(): array
    {
        return $this->commentRepository->findAll();
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
