<?php

namespace App\DataFixtures\ORM;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @codeCoverageIgnore
 */
class LoadPostData extends Fixture implements OrderedFixtureInterface
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
     * @throws \Exception
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
                ->setTrends($this->getRandomTrends(5))
                ->setImage(
                    $this->hasReference("image_$i") ? $this->getReference("image_$i") : null
                );

            $this->setReference("post_$i", $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @param int $max
     * @return array
     * @throws \Exception
     */
    protected function getRandomTrends(int $max = 5): array
    {
        $amount = random_int(0, $max);

        $trends = [];

        for ($i = 0; $i < $amount; $i++) {
            $trend = $this->getReference('trend_' . random_int(0, LoadTrendData::AMOUNT));

            in_array($trend, $trends) ? $i-- : $trends[] = $trend;
        }

        return $trends;
    }

    /**
     * {@inheritDoc|
     */
    public function getOrder(): int
    {
        return 3;
    }
}
