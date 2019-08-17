<?php

namespace App\Service;

use App\Entity\Developer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * @return Developer[]|object[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @return Developer[]|object[]
     */
    public function findActive(): array
    {
        return $this->repository->findBy(['isDeleted' => false]);
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
}
