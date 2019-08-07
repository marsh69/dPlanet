<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Image
 * @covers \App\Entity\Image
 */
class ImageTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Image::class;
}
