<?php

namespace App\Tests\Unit\Model;

use App\Model\ApiListResponse;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Model\ApiListResponse
 */
class ApiListResponseTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getOffset
     * @covers ::getCount
     * @covers ::getData
     * @covers ::getTotal
     * @covers ::getLimit
     */
    public function testIfConstructorConstructsProperly(): void
    {
        $apiResponse = new ApiListResponse([1, 2, 3], 10, 2, 20);

        $this->assertEquals(10, $apiResponse->getLimit());
        $this->assertEquals(20, $apiResponse->getTotal());
        $this->assertEquals(2, $apiResponse->getOffset());
        $this->assertEquals(3, $apiResponse->getCount());
        $this->assertEquals([1, 2, 3], $apiResponse->getData());
    }
}
