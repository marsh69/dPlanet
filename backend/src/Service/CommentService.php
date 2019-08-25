<?php

namespace App\Service;

use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class CommentService
{
    use DatabaseEntityCountTrait;

    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $commentRepository */
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
     * @param int $limit
     * @param int $offset
     * @return Comment[]|object[]
     */
    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->commentRepository->findBy([], [], $limit, $offset);
    }

    /**
     * @return int
     * @throws NonUniqueResultException
     */
    public function getCount(): int
    {
        return $this->getEntityCount(Comment::class);
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
        $comment->setIsDeleted(true);

        $this->em->persist($comment);
        $this->em->flush();
    }
}
