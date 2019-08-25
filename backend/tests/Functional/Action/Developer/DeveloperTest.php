<?php

namespace App\Tests\Functional\Action\Developer;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * TODO: Add image upload
 */
class DeveloperTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;
    use JsonRequestTrait;

    /**
     * @return void
     */
    public function testIfDeveloperCanBeRegistered(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe27@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $content = json_decode($response->getContent());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('TheLegend27', $content->username);
        $this->assertEquals('John Doe', $content->fullName);
    }

    /**
     * @return void
     */
    public function testIfDeveloperIsValidated(): void
    {
        $developer = [
            'username' => '',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe27@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfNoDuplicateUsersCanBeCreated(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe27@gmail.com'
        ];

        $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);

        $developer2 = [
            'username' => 'TheLegend27',
            'plainPassword' => 'TheMyth',
            'firstname' => 'Alice',
            'lastname' => 'Alison',
            'email' => 'johndoe27@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer2);

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testIfNewDeveloperCanLogin(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $newUserId = json_decode($response->getContent())->id;

        $this->becomeUser('TheLegend27', '28legendzzz');
        $this->assertEquals($newUserId, $this->currentUserId);
    }

    /**
     * TODO: Fix validation for updates
     *
     * @return void
     */
    public function testIfDeveloperCanBeEdited(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $newUserId = json_decode($response->getContent())->id;

        $this->becomeUser('admin', 'admin');

        $url = "/api/developers/{$newUserId}";

        $newDeveloper = [
            'username' => 'TheLegend28',
            'plainPassword' => 'newPass',
            'firstname' => 'Alex',
            'lastname' => 'Doe',
            'email' => 'alexdoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newDeveloper);
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('TheLegend27', $content->username);
    }

    /**
     * TODO: Fix validation for updates
     *
     * @return void
     */
    public function testIfDeveloperIsValidatedWhenEdited(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $newUserId = json_decode($response->getContent())->id;

        $this->becomeUser('admin', 'admin');

        $url = "/api/developers/{$newUserId}";

        $newDeveloper = [
            'username' => '',
            'plainPassword' => '',
            'firstname' => '',
            'lastname' => '',
            'email' => ''
        ];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newDeveloper);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfDeveloperCanBeDeleted(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $newUserId = json_decode($response->getContent())->id;

        $this->becomeUser('admin', 'admin');

        $url = "/api/developers/{$newUserId}";

        $this->client->request(Request::METHOD_DELETE, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('TheLegend27', $content->username);
    }

    /**
     * @return void
     */
    public function testIfDeveloperCanNotBeDeletedByUser(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $newUserId = json_decode($response->getContent())->id;

        $this->becomeUser('developer', 'developer');

        $url = "/api/developers/{$newUserId}";

        $this->client->request(Request::METHOD_DELETE, $url);

        $response = $this->client->getResponse();

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testIfDeveloperCanBeShown(): void
    {
        $developer = [
            'username' => 'TheLegend27',
            'plainPassword' => '28legendzzz',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@gmail.com'
        ];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/developers/register', $developer);
        $newUserId = json_decode($response->getContent())->id;

        $this->becomeUser('admin', 'admin');

        $url = "/api/developers/{$newUserId}";

        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('TheLegend27', $content->username);
    }
}
