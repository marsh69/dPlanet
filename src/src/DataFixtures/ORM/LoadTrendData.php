<?php

namespace App\DataFixtures\ORM;

use App\Entity\Comment;
use App\Entity\Trend;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class LoadTrendData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 50;

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
            $trend = (new Trend())
                ->setName($this->faker->word);

            $this->setReference("trend_$i", $trend);

            $manager->persist($trend);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc|
     */
    public function getOrder(): int
    {
        return 1;
    }
}