<?php

namespace App\DataFixtures\ORM;

use App\Entity\Notification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @codeCoverageIgnore
 */
class LoadNotificationData extends Fixture implements OrderedFixtureInterface
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
        for ($i = 0; $i < LoadDeveloperData::AMOUNT; $i++) {
            for ($notifNr = 0; $notifNr < self::AMOUNT; $notifNr++) {
                $notification = (new Notification())
                    ->setMessage($this->faker->realText(30))
                    ->setIsViewed($this->faker->boolean)
                    ->setIsOpened($this->faker->boolean)
                    ->setReceiver(
                        $this->getReference("user_$i")
                    )
                    ->setSender(
                        $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                    );

                $manager->persist($notification);
            }
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
