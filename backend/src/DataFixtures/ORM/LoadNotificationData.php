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
    const AMOUNT = 40;

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
            $notification = (new Notification())
                ->setSender(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setReceiver(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setIsOpened($this->faker->boolean(15))
                ->setIsViewed($this->faker->boolean(30))
                ->setMessage($this->faker->realText(100));

            $this->setReference("notification_$i", $notification);

            $manager->persist($notification);
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
