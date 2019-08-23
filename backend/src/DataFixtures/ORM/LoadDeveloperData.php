<?php

namespace App\DataFixtures\ORM;

use App\Entity\Developer;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * @codeCoverageIgnore
 */
class LoadDeveloperData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 20;

    /** @var Generator $faker */
    protected $faker;
    /** @var string $adminPassword */
    protected $adminPassword;
    /** @var string $developerPassword */
    protected $developerPassword;

    /**
     * LoadDeveloperData constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('en_EN');

        $bcrypt = new BCryptPasswordEncoder(16);
        $this->adminPassword = $bcrypt->encodePassword('admin', '');
        $this->developerPassword = $bcrypt->encodePassword('developer', '');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $admin = (new Developer())
            ->setUsername('admin')
            ->setPassword($this->adminPassword)
            ->setFirstName('admin')
            ->setLastName('admin')
            ->setEnabled(true)
            ->setSuperAdmin(true)
            ->setEmail('admin@dplanet.com');

        $manager->persist($admin);
        $this->setReference('user_0', $admin);

        $developer = (new Developer())
            ->setUsername('developer')
            ->setPassword($this->developerPassword)
            ->setFirstName('developer')
            ->setLastName('developer')
            ->setEnabled(true)
            ->setRoles(['ROLE_DEVELOPER'])
            ->setEmail('developer@dplanet.com');

        $manager->persist($developer);
        $this->setReference('user_1', $developer);

        for ($i = 2; $i < self::AMOUNT + 1; $i++) {
            /** @var Image $image */
            $image = $this->hasReference("image_$i") ? $this->getReference("image_$i") : null;

            $developer = (new Developer())
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setEmail($this->faker->email)
                ->setEnabled($this->faker->boolean(80))
                ->setPassword($this->developerPassword)
                ->setRoles(['ROLE_DEVELOPER'])
                ->setUsername($this->faker->userName)
                ->setProfileImage($image);

            $this->setReference("user_$i", $developer);

            $manager->persist($developer);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder(): int
    {
        return 2;
    }
}
