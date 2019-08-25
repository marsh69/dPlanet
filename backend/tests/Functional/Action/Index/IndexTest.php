<?php

namespace App\Tests\Functional\Action\Index;

use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class IndexTest extends FixtureAwareTestCase
{
    /**
     * TODO: Add more in-depth testing
     *
     * @return void
     */
    public function testIfIndexRendersProperly(): void
    {
        $this->client->request(Request::METHOD_GET, '/api');

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
