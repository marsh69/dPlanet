<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

trait JsonRequestTrait
{
    /** @var Client $client */
    protected $client;

    /**
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param array $content
     * @return Response
     */
    protected function jsonRequest(
        string $method,
        string $url,
        array $content = [],
        array $parameters = [],
        array $files = [],
        array $server = []
    ): Response {
        $serverParams = array_merge(['CONTENT_TYPE' => 'application/json'], $server);

        $this->client->request($method, $url, $parameters, $files, $serverParams, json_encode($content));

        return $this->client->getResponse();
    }
}
