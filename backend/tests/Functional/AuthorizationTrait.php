<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;

trait AuthorizationTrait
{
    /** @var Client $client */
    protected $client;
    /** @var string $currentUserId */
    protected $currentUserId;

    /**
     * Add a authorization header to the client object with a JWT token
     * belonging to a certain user
     *
     * @param string $username of the user you want to authenticate
     * @param string $password of the user you want to authenticate
     * @return void
     */
    protected function becomeUser(string $username, string $password): void
    {
        $credentials = json_encode([
            'username' => $username,
            'password' => $password,
        ]);

        $this->client->request(Request::METHOD_POST, '/api/token', [], [], [], $credentials);

        $content = json_decode(
            $this->client->getResponse()->getContent()
        );

        try {
            $this->currentUserId = $content->user->id;
            $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $content->token);
        } catch (\Exception $exception) {
            echo PHP_EOL . PHP_EOL;
            echo "Something went wrong while logging in! Message: {$exception->getMessage()}" . PHP_EOL;
            var_dump($content);
            echo PHP_EOL . PHP_EOL;
        }
    }
}
