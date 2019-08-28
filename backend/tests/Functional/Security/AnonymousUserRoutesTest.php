<?php

namespace App\Tests\Functional\Security;

use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class AnonymousUserRoutesTest extends FixtureAwareTestCase
{
    /**
     * Test if an anonymous user is not able to utilize member-only routes
     *
     * @dataProvider urlProvider
     * @param string $location
     * @param string $method
     */
    public function testIfAnonymousIsUnauthorized(string $location, string $method): void
    {
        $this->client->request($method, $location);
        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * A few randomly chosen GET routes to see if it works
     *
     * @return array
     */
    public function urlProvider(): array
    {
        return [
            ['/api/posts', Request::METHOD_GET],
        ];
    }
}
