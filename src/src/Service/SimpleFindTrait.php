<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;

trait SimpleFindTrait
{
    /** @var ObjectRepository $repository */
    protected $repository;

    /**
     * @return Collection|object[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $criteria
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     * @return Collection|object[]
     */
    public function findBy(array $criteria, array $order = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy($criteria, $order, $limit, $offset);
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function find(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $criteria
     * @return Collection|object[]
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}