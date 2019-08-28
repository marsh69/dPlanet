<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class PostService
{
    use DatabaseEntityCountTrait;

    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $repository */
    protected $repository;

    /**
     * PostService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Post::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Post[]|object[]
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
        return $this->getEntityCount(Post::class);
    }

    /**
     * @param Post $post
     */
    public function save(Post $post): void
    {
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
