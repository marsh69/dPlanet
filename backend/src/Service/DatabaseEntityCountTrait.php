<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

trait DatabaseEntityCountTrait
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /**
     * @param string $entityName
     * @return int
     * @throws NonUniqueResultException
     */
    public function getEntityCount(string $entityName): int
    {
        return $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from($entityName, 'c')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
