<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

trait JsonRequestTrait
{
    /** @var Client $client */
    protected $client;

    /**
     * Send a POST/PUT/DELETE request with json data
     *
     * @param string $method HTTP Method like PUT, POST or DELETE
     * @param string $url The URL you want to send the request to
     * @param array $content The data you want to send to the endpoint
     * @param array $parameters Request parameters to add to the request
     * @param array $files Any files to add to the request
     * @param array $server Any other server parameters
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
