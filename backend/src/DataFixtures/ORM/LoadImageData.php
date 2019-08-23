<?php


namespace App\DataFixtures\ORM;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @codeCoverageIgnore
 */
class LoadImageData extends Fixture implements OrderedFixtureInterface
{
    const AMOUNT = 3;

    /** @var string folder where */
    const SRC_FOLDER = __DIR__ . '/../../../assets/fixtureimages';

    /** @var Generator $faker */
    protected $faker;
    /** @var Finder $finder */
    protected $finder;
    /** @var Filesystem $fs */
    protected $fs;

    /**
     * LoadDeveloperData constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('en_EN');
        $this->finder = new Finder();
        $this->fs = new Filesystem();
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $files = $this->finder
            ->files()
            ->in(self::SRC_FOLDER)
            ->getIterator();

        $count = 0;
        foreach ($files as $file => $fileObject) {
            $this->fs->copy($file, "$file.test");

            $image = (new Image())
                ->setResource(
                    new UploadedFile("$file.test", "fixture_$count", mime_content_type($file), null, true)
                );

            $manager->persist($image);

            $this->setReference("image_$count", $image);

            $count++;
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
