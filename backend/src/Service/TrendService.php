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
     * @return Trend[]|object[]
     */
    public function findActive(): array
    {
        return $this->repository->findBy(['isDeleted' => false]);
    }

    /**
     * @param Trend $image
     */
    public function save(Trend $image): void
    {
        $this->em->persist($image);
        $this->em->flush();
    }
}
