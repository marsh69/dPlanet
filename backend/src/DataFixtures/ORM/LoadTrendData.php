<?php

namespace App\DataFixtures\ORM;

use App\Entity\Trend;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @codeCoverageIgnore
 */
class LoadTrendData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 20;
    const TRENDS = [
        'Java',
        'Deep Learning',
        'Scrum',
        'Kanban',
        'C#', #5
        'C++',
        'C',
        'PHP',
        'Javascript',
        'ReactJS', #10
        'AngularJS',
        'VueJS',
        'Docker',
        'Kubernetes',
        'K8S', #15
        'OpenShift',
        'Ansible',
        'Symfony',
        'EntityFramework',
        'A.I.', #20
        'UX'
    ];

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
        foreach (self::TRENDS as $key => $trendName) {
            $trend = (new Trend())
                ->setName($trendName);

            $this->setReference("trend_$key", $trend);

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
