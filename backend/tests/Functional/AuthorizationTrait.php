<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;

trait AuthorizationTrait
{
    /** @var array $cachedTokens to make performance slightly better */
    protected static $cachedTokens = [];

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
        $cacheKey = crc32($username . $password);

        if ($this->isCached($cacheKey)) {
            $this->applyCache($cacheKey);
            return;
        }

        $credentials = json_encode(['username' => $username, 'password' => $password]);

        $this->client->request(Request::METHOD_POST, '/api/token', [], [], [], $credentials);

        $content = json_decode($this->client->getResponse()->getContent());

        try {
            $this->currentUserId = $content->user->id;
            $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $content->token);

            self::$cachedTokens[$cacheKey] = ['userId' => $content->user->id, 'token' => $content->token];
        } catch (\Exception $exception) {
            echo "Something went wrong while logging in! Message: {$exception->getMessage()}" . PHP_EOL;
            var_dump($content);
        }
    }

    /**
     * Check if the list of cached tokens contains the cache key
     *
     * @param string $cacheKey
     * @return bool
     */
    protected function isCached(string $cacheKey): bool
    {
        return isset(self::$cachedTokens[$cacheKey]);
    }

    /**
     * Set the server parameter and user id from the cache list
     *
     * @param string $cacheKey
     */
    protected function applyCache(string $cacheKey): void
    {
        $this->currentUserId = self::$cachedTokens[$cacheKey]['userId'];
        $this->client->setServerParameter(
            'HTTP_AUTHORIZATION',
            'Bearer ' . self::$cachedTokens[$cacheKey]['token']
        );
    }
}
