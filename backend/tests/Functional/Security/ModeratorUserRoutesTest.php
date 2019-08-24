<?php

namespace App\Tests\Functional\Security;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class ModeratorUserRoutesTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;

    /**
     * @dataProvider urlProvider
     * @param string $location
     * @param string $method
     */
    public function testIfModeratorIsAuthorized(string $location, string $method): void
    {
        $this->setAuthorizationToken('moderator', 'moderator');

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
            ['/api/posts/test', Request::METHOD_DELETE],
            ['/api/posts/test', Request::METHOD_PUT],
            ['/api/comments/test', Request::METHOD_DELETE],
            ['/api/comments/test', Request::METHOD_PUT],
        ];
    }
}
