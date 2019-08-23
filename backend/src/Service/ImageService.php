<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class ImageService
{
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;

    /**
     * ImageService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Image::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Image[]|object[]
     */
    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy([], [], $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array $order
     * @param int $limit
     * @param int $offset
     * @return Image[]|object[]
     */
    public function findBy(array $criteria, array $order = [], ?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy($criteria, $order, $limit, $offset);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        try {
            return $this->em->createQueryBuilder()
                ->select('COUNT(i.id)')
                ->from('App\Entity\Image', 'i')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return -1;
        }
    }


    /**
     * @param Image $image
     */
    public function save(Image $image): void
    {
        $this->em->persist($image);
        $this->em->flush();
    }

    /**
     * @param Image $image
     */
    public function delete(Image $image): void
    {
        $image->setIsDeleted(true);

        $this->em->persist($image);
        $this->em->flush();
    }
}
