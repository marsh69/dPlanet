<?php

namespace App\Tests\Unit\Entity;

use App\Entity\IdTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entity\IdTrait
 */
class IdTraitTest extends TestCase
{
    /**
     * @covers ::setId
     * @covers ::getId
     */
    public function testIfIdCanBeGetAndSet(): void
    {
        $class = new class {
            use IdTrait;
        };

        $class->setId('test');

        $this->assertEquals('test', $class->getId());
    }
}
