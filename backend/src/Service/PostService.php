<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;
    /** @var UploadService $uploadService */
    protected $uploadService;

    /**
     * PostService constructor.
     * @param EntityManagerInterface $em
     * @param UploadService $uploadService
     */
    public function __construct(EntityManagerInterface $em, UploadService $uploadService)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Post::class);
        $this->uploadService = $uploadService;
    }

    /**
     * @return Post[]|object[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $criteria
     * @param array $order
     * @return Post[]|object[]
     */
    public function findBy(array $criteria, array $order = []): array
    {
        return $this->repository->findBy($criteria, $order);
    }

    /**
     * @param Post $post
     */
    public function save(Post $post): void
    {
        if ($post->getImage()) {
            $newImage = $this->uploadService->uploadImage($post->getImage());
            $post->setImage($newImage);
        }

        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * @param Post $post
     */
    public function delete(Post $post): void
    {
        $post->setIsDeleted(true);
        $this->em->persist($post);
        $this->em->flush();
    }
}
