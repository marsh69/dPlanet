<?php

namespace App\Tests\Functional\Security;

use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class AnonymousUserRoutesTest extends FixtureAwareTestCase
{
    /**
     * @dataProvider urlProvider
     * @param string $location
     * @param string $method
     */
    public function testIfRouteIsProtected(string $location, string $method): void
    {
        $this->client->request($method, $location);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(401, $content->code);
    }

    /**
     * @return array
     */
    public function urlProvider(): array
    {
        return [
            ['/api/trends', Request::METHOD_GET],
            ['/api/trends', Request::METHOD_POST],
            ['/api/trends/test', Request::METHOD_DELETE],
            ['/api/trends/test', Request::METHOD_PUT],
            ['/api/developers', Request::METHOD_GET],
            ['/api/developers/test', Request::METHOD_DELETE],
            ['/api/developers/test', Request::METHOD_PUT],
            ['/api/posts', Request::METHOD_GET],
            ['/api/posts', Request::METHOD_POST],
            ['/api/posts/test', Request::METHOD_DELETE],
            ['/api/posts/test', Request::METHOD_PUT],
            ['/api/comments', Request::METHOD_GET],
            ['/api/comments/test', Request::METHOD_DELETE],
            ['/api/comments/test', Request::METHOD_PUT],
            ['/api/likes', Request::METHOD_GET],
            ['/api/posts/0/likes', Request::METHOD_GET],
            ['/api/posts/0/comments', Request::METHOD_GET],
            ['/api/posts/0/trends', Request::METHOD_GET],
        ];
    }
}
