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
     * @return Image[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param Image $image
     */
    public function save(Image $image): void
    {
        // TODO: Add save logic for the image itself
        $this->em->persist($image);
        $this->em->flush();
    }
}
