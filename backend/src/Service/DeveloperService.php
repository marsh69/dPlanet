<?php

namespace App\Service;

use App\Entity\Developer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class DeveloperService
{
    use DatabaseEntityCountTrait;

    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;

    /**
     * DeveloperService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Developer::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Developer[]|object[]
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
        return $this->getEntityCount(Developer::class);
    }

    /**
     * @param Developer $developer
     */
    public function save(Developer $developer): void
    {
        $this->em->persist($developer);
        $this->em->flush();
    }

    /**
     * Developers are truly removed instead of deactivated due to these objects
     * containing Personal Identifiable Information and the GDPR
     *
     * @param Developer $developer
     */
    public function delete(Developer $developer): void
    {
        $this->em->remove($developer);
        $this->em->flush();
    }
}
