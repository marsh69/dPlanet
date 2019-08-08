<?php

namespace App\DataFixtures\ORM;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class LoadPostData extends Fixture implements OrderedFixtureInterface
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
            $post = (new Post())
                ->setPostedBy(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setBody($this->faker->realText(400))
                ->setIsClosedByUser($this->faker->boolean(90))
                ->setTrends(new ArrayCollection([
                    $this->getReference('trend_'. random_int(0, LoadTrendData::AMOUNT))
                ]));

            $this->setReference("post_$i", $post);

            $manager->persist($post);
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