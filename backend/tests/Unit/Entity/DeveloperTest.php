<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Developer;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @coversDefaultClass \App\Entity\Developer
 * @covers \App\Entity\Developer
 */
class DeveloperTest extends TestCase
{
    use EntityGetSetTestTrait;

    /** @var string $class */
    protected $class = Developer::class;

    /** @var array $excludedGetters */
    protected $excludedGetters = ['getRoles'];
}
