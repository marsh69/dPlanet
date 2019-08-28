<?php

namespace App\Service;

use App\Entity\Trend;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class TrendService
{
    use DatabaseEntityCountTrait;

    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;

    /**
     * TrendService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Trend::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Trend[]|object[]
     */
    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy([], [], $limit, $offset);
    }

    /**
     * @return int
     * @throws NonUniqueResultException
     */
    public function getCount(): int
    {
        return $this->getEntityCount(Trend::class);
    }

    /**
     * @param Trend $trend
     */
    public function save(Trend $trend): void
    {
        $this->em->persist($trend);
        $this->em->flush();
    }

    /**
     * @param Trend $trend
     */
    public function delete(Trend $trend): void
    {
        $trend->setIsDeleted(true);
        $this->em->persist($trend);
        $this->em->flush();
    }
}
