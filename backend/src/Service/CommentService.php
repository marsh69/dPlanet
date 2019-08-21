<?php

namespace App\Service;

use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class CommentService
{
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
     * @param array $criteria
     * @param array $order
     * @param int $limit
     * @param int $offset
     * @return Comment[]|object[]
     */
    public function findBy(array $criteria, array $order = [], ?int $limit = null, ?int $offset = null): array
    {
        return $this->commentRepository->findBy($criteria, $order, $limit, $offset);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        try {
            return $this->em->createQueryBuilder()
                ->select('COUNT(c.id)')
                ->from('App\Entity\Comment', 'c')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return -1;
        }
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
