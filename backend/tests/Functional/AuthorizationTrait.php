<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;

trait AuthorizationTrait
{
    /** @var Client $client */
    protected $client;

    /**
     * @param string $username
     * @param string $password
     * @return void
     */
    protected function setAuthorizationToken(string $username, string $password): void
    {
        $credentials = json_encode([
            'username' => $username,
            'password' => $password,
        ]);

        $this->client->request(Request::METHOD_POST, '/api/token', [], [], [], $credentials);

        $content = json_decode(
            $this->client->getResponse()->getContent()
        );

        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $content->token);
    }
}
