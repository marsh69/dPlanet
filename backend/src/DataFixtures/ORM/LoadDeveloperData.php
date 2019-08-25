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
    const AMOUNT = 10;

    /** @var Generator $faker */
    protected $faker;
    /** @var string $adminPassword */
    protected $adminPassword;
    /** @var string $developerPassword */
    protected $developerPassword;
    /** @var string $moderatorPassword */
    protected $moderatorPassword;

    /**
     * LoadDeveloperData constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('en_EN');

        $bcrypt = new BCryptPasswordEncoder(16);
        $this->adminPassword = $bcrypt->encodePassword('admin', '');
        $this->moderatorPassword = $bcrypt->encodePassword('moderator', '');
        $this->developerPassword = $bcrypt->encodePassword('developer', '');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $admin = (new Developer())
            ->setEnabled(true)
            ->setUsername('admin')
            ->setPassword($this->adminPassword)
            ->setFirstName('admin')
            ->setLastName('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@dplanet.com');

        $manager->persist($admin);
        $this->setReference('user_0', $admin);

        $moderator = (new Developer())
            ->setEnabled(true)
            ->setUsername('moderator')
            ->setPassword($this->moderatorPassword)
            ->setFirstName('moderator')
            ->setLastName('moderator')
            ->setRoles(['ROLE_MODERATOR'])
            ->setEmail('moderator@dplanet.com');

        $manager->persist($moderator);
        $this->setReference('user_1', $moderator);

        $developer = (new Developer())
            ->setEnabled(true)
            ->setUsername('developer')
            ->setPassword($this->developerPassword)
            ->setFirstName('developer')
            ->setLastName('developer')
            ->setRoles(['ROLE_DEVELOPER'])
            ->setEmail('developer@dplanet.com');

        $manager->persist($developer);
        $this->setReference('user_2', $developer);

        for ($i = 3; $i < self::AMOUNT + 1; $i++) {
            /** @var Image $image */
            $image = $this->hasReference("image_$i") ? $this->getReference("image_$i") : null;

            $developer = (new Developer())
                ->setEnabled(true)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setEmail($this->faker->email)
                ->setPassword($this->developerPassword)
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
