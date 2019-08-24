<?php

namespace App\Tests\Functional\Security;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class AdminUserRoutesTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;

    /**
     * Test if an admin does not get 401 or 403 errors when requesting admin urls
     *
     * @dataProvider urlProvider
     * @param string $location
     * @param string $method
     */
    public function testIfAdminIsAuthorized(string $location, string $method): void
    {
        $this->becomeUser('admin', 'admin');

        $this->client->request($method, $location);
        $statusCode = $this->client->getResponse()->getStatusCode();

        $this->assertNotEquals(401, $statusCode);
        $this->assertNotEquals(403, $statusCode);
    }

    /**
     * @return array
     */
    public function urlProvider(): array
    {
        return [
            ['/api/trends/test', Request::METHOD_DELETE],
            ['/api/trends/test', Request::METHOD_PUT],
            ['/api/developers', Request::METHOD_GET],
            ['/api/developers/test', Request::METHOD_DELETE],
            ['/api/developers/test', Request::METHOD_PUT],
            ['/api/posts/test', Request::METHOD_DELETE],
            ['/api/posts/test', Request::METHOD_PUT],
            ['/api/comments/test', Request::METHOD_DELETE],
            ['/api/comments/test', Request::METHOD_PUT],
            ['/api/likes', Request::METHOD_GET],
            ['/api/posts/0/likes', Request::METHOD_GET],
            ['/api/posts/0/likes', Request::METHOD_DELETE],
        ];
    }
}
