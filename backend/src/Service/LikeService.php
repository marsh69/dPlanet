<?php

namespace App\Service;

use App\Entity\Like;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class LikeService
{
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $likeRepository */
    protected $likeRepository;

    /**
     * LikeService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->likeRepository = $em->getRepository(Like::class);
    }

    /**
     * @return Like[]|object[]
     */
    public function findAll(): array
    {
        return $this->likeRepository->findAll();
    }

    /**
     * @param array $criteria
     * @param array $order
     * @return Like[]|object[]
     */
    public function findBy(array $criteria, array $order = []): array
    {
        return $this->likeRepository->findBy($criteria, $order);
    }

    /**
     * @param array $criteria
     * @return Like|null
     */
    public function findOneBy(array $criteria): ?Like
    {
        return $this->likeRepository->findOneBy($criteria);
    }

    /**
     * @param Like $like
     */
    public function save(Like $like): void
    {
        $this->em->persist($like);
        $this->em->flush();
    }

    /**
     * @param Like $like
     */
    public function delete(Like $like): void
    {
        $like->setIsDeleted(true);
        $this->em->persist($like);
        $this->em->flush();
    }
}
