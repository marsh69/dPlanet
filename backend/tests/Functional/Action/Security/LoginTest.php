<?php

namespace App\Tests\Functional\Action\Security;

use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class LoginTest extends FixtureAwareTestCase
{
    /**
     * @return void
     */
    public function testLogin(): void
    {
        $credentials = json_encode([
            'username' => 'developer',
            'password' => 'developer'
        ]);

        $this->client->request(Request::METHOD_POST, '/api/token', [], [], [], $credentials);

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertNotEmpty($response->token);
        $this->assertEquals('developer', $response->user->username);
        $this->assertEquals('developer', $response->user->firstName);
        $this->assertEquals('developer', $response->user->lastName);
    }
}
