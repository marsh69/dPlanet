<?php

namespace App\Tests\Functional\Action\Notification;

use App\DataFixtures\ORM\LoadNotificationData;
use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class NotificationTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;
    use JsonRequestTrait;

    /**
     * @return void
     */
    public function testIfAllNotificationsCanBeFetched(): void
    {
        $this->becomeUser('admin', 'admin');

        $this->client->request(Request::METHOD_GET, '/api/notifications');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertObjectHasAttribute('_data', $content);

        $this->assertCount(50, $content->_data);

        $randomPost = $content->_data[0];

        $this->assertObjectHasAttribute('message', $randomPost);
        $this->assertObjectHasAttribute('viewed', $randomPost);
        $this->assertObjectHasAttribute('opened', $randomPost);
        $this->assertObjectHasAttribute('id', $randomPost);
    }

    /**
     * @return void
     */
    public function testIfOneNotificationCanBeFetched(): void
    {
        $this->becomeUser('admin', 'admin');

        $this->client->request(Request::METHOD_GET, '/api/notifications');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());

        $url = "/api/notifications/{$content->_data[0]->id}";

        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());

        $this->assertObjectHasAttribute('message', $content);
        $this->assertObjectHasAttribute('viewed', $content);
        $this->assertObjectHasAttribute('opened', $content);
        $this->assertObjectHasAttribute('id', $content);
    }
}
