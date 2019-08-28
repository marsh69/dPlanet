<?php

namespace App\Service;

use App\Entity\Like;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class LikeService
{
    use DatabaseEntityCountTrait;

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
     * @param int $limit
     * @param int $offset
     * @return Like[]|object[]
     */
    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->likeRepository->findBy([], [], $limit, $offset);
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
     * @return int
     * @throws NonUniqueResultException
     */
    public function getCount(): int
    {
        return $this->getEntityCount(Like::class);
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
