<?php

namespace App\Tests\Functional\Action\Security;

use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class LoginTest extends FixtureAwareTestCase
{
    use JsonRequestTrait;

    /**
     * @return void
     */
    public function testLogin(): void
    {
        $credentials = [
            'username' => 'developer',
            'password' => 'developer'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/token', $credentials);
        $content = json_decode($response->getContent());

        $this->assertNotEmpty($content->token);
        $this->assertEquals('developer', $content->user->username);
        $this->assertEquals('developer', $content->user->firstName);
        $this->assertEquals('developer', $content->user->lastName);
    }

    /**
     * @return void
     */
    public function testLoginWithWrongPassword(): void
    {
        $credentials = [
            'username' => 'test',
            'password' => 'john'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/token', $credentials);
        $content = json_decode($response->getContent());

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Bad credentials.', $content->message);
        $this->assertEquals(401, $content->code);
    }

    /**
     * @return void
     */
    public function testLoginWithEmptyObject(): void
    {
        $credentials = [];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/token', $credentials);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
