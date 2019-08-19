<?php

namespace App\Service;

use App\Entity\Trend;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrendService
{
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
     * @return Trend[]|object[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
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
