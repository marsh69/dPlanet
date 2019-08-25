<?php

namespace App\Tests\Functional\Action\Developer\Notification;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class DeveloperTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;
    use JsonRequestTrait;

    /**
     * @return void
     */
    public function testIfDeveloperNotificationsCanBeFetched(): void
    {
        $this->becomeUser('admin', 'admin');

        $this->client->request(Request::METHOD_GET, "/api/developers/{$this->currentUserId}/notifications");

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());

        $randomPost = $content->_data[0];

        $this->assertObjectHasAttribute('message', $randomPost);
        $this->assertObjectHasAttribute('viewed', $randomPost);
        $this->assertObjectHasAttribute('opened', $randomPost);
        $this->assertObjectHasAttribute('id', $randomPost);
    }
}
