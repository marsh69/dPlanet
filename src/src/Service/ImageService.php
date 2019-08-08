<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImageService
{
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;
    /** @var UploadService $uploadService */
    protected $uploadService;

    /**
     * ImageService constructor.
     * @param EntityManagerInterface $em
     * @param UploadService $uploadService
     */
    public function __construct(EntityManagerInterface $em, UploadService $uploadService)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Image::class);
    }

    /**
     * @return Image[]|object[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @return Image[]|object[]
     */
    public function findActive(): array
    {
        return $this->repository->findBy(['isDeleted' => false]);
    }

    /**
     * @param Image $image
     */
    public function save(Image $image): void
    {
        $this->uploadService->uploadImage($image);

        $this->em->persist($image);
        $this->em->flush();
    }
}
