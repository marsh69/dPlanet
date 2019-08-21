<?php

namespace App\Service;

use App\Entity\Developer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class DeveloperService
{
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;
    /** @var UploadService $uploadService */
    protected $uploadService;

    /**
     * DeveloperService constructor.
     * @param EntityManagerInterface $em
     * @param UploadService $uploadService
     */
    public function __construct(EntityManagerInterface $em, UploadService $uploadService)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Developer::class);
        $this->uploadService = $uploadService;
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
     * @param array $criteria
     * @param array $order
     * @param int $limit
     * @param int $offset
     * @return Developer[]|object[]
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
                ->select('COUNT(d.id)')
                ->from('App\Entity\Developer', 'd')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return -1;
        }
    }

    /**
     * @param Developer $developer
     */
    public function save(Developer $developer): void
    {
        if ($developer->getProfileImage()) {
            $newImage = $this->uploadService->uploadImage($developer->getProfileImage());
            $developer->setProfileImage($newImage);
        }

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
