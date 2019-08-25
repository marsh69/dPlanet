<?php

namespace App\Tests\Intergration\EventListener;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\EventListener\ImageUploadListener
 * @covers ::__construct
 */
class ImageUploadHandlerTest extends TestCase
{
    /**
     * @covers ::prePersist
     */
    public function testIfImageIsUploadedOnPrePersist(): void
    {
        $this->markTestIncomplete('Work in progress');
    }
}
