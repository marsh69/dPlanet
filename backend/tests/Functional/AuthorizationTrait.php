<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;

trait AuthorizationTrait
{
    /** @var Client $client */
    protected $client;

    /**
     * @param string $username of the user you want to authenticate
     * @param string $password of the user you want to authenticate
     * @return void
     */
    protected function becomeUser(string $username, string $password): void
    {
        $this->client->setServerParameter('PHP_AUTH_USER', $username);
        $this->client->setServerParameter('PHP_AUTH_PW', $password);
    }
}
