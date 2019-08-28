<?php

namespace App\Service;

use App\Entity\Notification;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class NotificationService
{
    use DatabaseEntityCountTrait;

    /** @var EntityManagerInterface $em */
    protected $em;
    /** @var ObjectRepository $notificationRepository */
    protected $notificationRepository;

    /**
     * NotificationService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->notificationRepository = $em->getRepository(Notification::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->notificationRepository->findBy([], [], $limit, $offset);
    }

    /**
     * @return int
     * @throws NonUniqueResultException
     */
    public function getCount(): int
    {
        return $this->getEntityCount(Notification::class);
    }
}
