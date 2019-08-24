<?php

namespace App\DataFixtures\ORM;

use App\Entity\Like;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @codeCoverageIgnore
 */
class LoadLikeData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 15;

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
            $like = (new Like())
                ->setDeveloper(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setPost(
                    $this->getReference('post_' . random_int(0, LoadPostData::AMOUNT))
                );

            $manager->persist($like);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc|
     */
    public function getOrder(): int
    {
        return 4;
    }
}
