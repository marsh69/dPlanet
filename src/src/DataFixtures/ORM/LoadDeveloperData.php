<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class LoadDeveloperData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 20;

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
    public function load(ObjectManager $manager): void
    {
        // TODO
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder(): int
    {
        return 1;
    }
}