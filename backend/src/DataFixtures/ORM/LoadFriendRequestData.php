<?php

namespace App\DataFixtures\ORM;

use App\Entity\FriendRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @codeCoverageIgnore
 */
class LoadFriendRequestData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 5;

    /** @var Generator $faker */
    protected $faker;

    /**
     * LoadDeveloperData constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('en_EN');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::AMOUNT + 1; $i++) {
            $friendRequest = (new FriendRequest())
                ->setReceiver(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setSender(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                );

            $this->setReference("friendrequest_$i", $friendRequest);

            $manager->persist($friendRequest);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc|
     */
    public function getOrder(): int
    {
        return 5;
    }
}
