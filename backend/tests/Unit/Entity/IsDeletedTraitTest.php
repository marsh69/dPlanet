<?php

namespace App\Tests\Unit\Entity;

use App\Entity\IsDeletedTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entity\IsDeletedTrait
 */
class IsDeletedTraitTest extends TestCase
{
    /**
     * @covers ::setIsDeleted
     * @covers ::isDeleted
     */
    public function testIfDeletedCanBeGetAndSet(): void
    {
        $class = new class {
            use IsDeletedTrait;
        };

        $class->setIsDeleted(true);
        $this->assertTrue($class->isDeleted());

        $class->setIsDeleted(false);
        $this->assertFalse($class->isDeleted());
    }
}
