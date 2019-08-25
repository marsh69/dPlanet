<?php

namespace App\Tests\Functional\Action\Trend;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class TrendTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;
    use JsonRequestTrait;

    /**
     * @return void
     */
    public function testIfListOfTrendsCanBeFetched(): void
    {
        $this->becomeUser('admin', 'admin');

        $this->client->request(Request::METHOD_GET, '/api/trends');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());

        $list = $content->_data;

        $this->assertCount(11, $list);

        $testItem = $list[0];

        $this->assertObjectHasAttribute('id', $testItem);
        $this->assertObjectHasAttribute('name', $testItem);
    }

    /**
     * @return void
     */
    public function testIfTrendCanBeCreated(): void
    {
        $this->becomeUser('admin', 'admin');

        $content = ['name' => 'New Trend'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/trends', $content);
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());

        $this->assertObjectHasAttribute('name', $content);
        $this->assertObjectHasAttribute('id', $content);

        $this->assertEquals('New Trend', $content->name);
        $this->assertEquals(0, $content->hits);
    }

    /**
     * @return void
     */
    public function testIfTrendIsValidated(): void
    {
        $this->becomeUser('admin', 'admin');

        $content = ['name' => 'b'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/trends', $content);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfTrendCanBeFetched(): void
    {
        $this->becomeUser('admin', 'admin');

        $content = ['name' => 'Secret trend'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/trends', $content);

        $content = json_decode($response->getContent());

        $url = "/api/trends/{$content->id}";

        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('Secret trend', $content->name);
    }

    /**
     * @return void
     */
    public function testIfTrendCanBeDeleted(): void
    {
        $this->becomeUser('admin', 'admin');

        $content = ['name' => 'Bad trend2'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/trends', $content);
        $content = json_decode($response->getContent());

        $url = "/api/trends/{$content->id}";

        $this->client->request(Request::METHOD_DELETE, $url);
        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testIfTrendCanBeEdited(): void
    {
        $this->becomeUser('admin', 'admin');

        $content = ['name' => 'Bad trend3'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/trends', $content);
        $content = json_decode($response->getContent());

        $url = "/api/trends/{$content->id}";
        $newContent = ['name' => 'Good trend'];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newContent);

        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('Good trend', $content->name);
    }

    /**
     * @return void
     */
    public function testIfTrendIsValidatedWhenEdited(): void
    {
        $this->becomeUser('admin', 'admin');

        $content = ['name' => 'Bad trend3'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/trends', $content);
        $content = json_decode($response->getContent());

        $url = "/api/trends/{$content->id}";
        $newContent = ['name' => 'G'];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newContent);

        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }
}
