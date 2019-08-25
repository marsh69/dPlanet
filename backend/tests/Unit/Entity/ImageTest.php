<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @coversDefaultClass \App\Entity\Image
 * @covers \App\Entity\Image
 */
class ImageTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Image::class;

    /**
     * @covers ::getImageUpdated
     * @throws \Exception
     */
    public function testIfGetImageUpdatedGetsSet(): void
    {
        $image = new Image();

        $old = $image->getImageUpdated();

        # TODO: Fix this absolute filename
        $image->setResource(new UploadedFile('/app/src/assets/fixtureimages/fixture_2.png', '', null, null, true));

        $this->assertNotEquals($old, $image->getImageUpdated());
    }
}
