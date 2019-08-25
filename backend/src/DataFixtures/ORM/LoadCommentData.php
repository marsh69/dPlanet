<?php

namespace App\DataFixtures\ORM;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @codeCoverageIgnore
 */
class LoadCommentData extends Fixture implements OrderedFixtureInterface
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
            $comment = (new Comment())
                ->setBody($this->faker->realText(300))
                ->setPostedBy(
                    $this->getReference('user_' . random_int(0, LoadDeveloperData::AMOUNT))
                )
                ->setPostedTo(
                    $this->getReference('post_' . random_int(0, LoadPostData::AMOUNT))
                );

            $this->setReference("comment_$i", $comment);

            $manager->persist($comment);
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
