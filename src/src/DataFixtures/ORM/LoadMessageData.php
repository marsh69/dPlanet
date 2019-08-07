<?php

namespace App\DataFixtures\ORM;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class LoadMessageData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 30;

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
            $message = (new Message())
                ->setIsOpened($this->faker->boolean(30))
                ->setReceiver(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setSender(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setBody($this->faker->realText(400));

            $this->setReference("message_$i", $message);

            $manager->persist($message);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc|
     */
    public function getOrder(): int
    {
        return 3;
    }
}