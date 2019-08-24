<?php

namespace App\Tests\Functional\Action\Post;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * TODO: Add image upload
 */
class PostTest extends FixtureAwareTestCase
{
    use JsonRequestTrait;
    use AuthorizationTrait;

    /**
     * @return void
     */
    public function testIfPostsCanBeFetched(): void
    {
        $this->becomeUser('developer', 'developer');

        $this->client->request(Request::METHOD_GET, '/api/posts');

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('_data', $content);

        $this->assertCount(26, $content->_data);

        $randomPost = $content->_data[0];

        $this->assertObjectHasAttribute('body', $randomPost);
        $this->assertObjectHasAttribute('id', $randomPost);
    }

    /**
     * @return void
     */
    public function testIfPostCanBeCreated(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Hello all! This is my first post :)', $content->body);
    }

    /**
     * @return void
     */
    public function testIfPostIsValidated(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'o'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfPostCanBeFetched(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}";

        $this->client->request(Request::METHOD_GET, $url);
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello all! This is my first post :)', $content->body);
    }

    /**
     * @return void
     */
    public function testIfPostCanBeEdited(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}";

        $newPost = ['body' => 'Oops sorry, this is actually my second post!'];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newPost);
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Oops sorry, this is actually my second post!', $content->body);
    }

    /**
     * @return void
     */
    public function testIfPostIsValidatedWhenEdited(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}";

        $newPost = ['body' => 'O'];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newPost);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfPostsCanNotBeDeletedByOthers(): void
    {
        $this->becomeUser('moderator', 'moderator');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}";

        $this->becomeUser('developer', 'developer');

        $this->client->request(Request::METHOD_DELETE, $url);
        $response = $this->client->getResponse();

        $this->assertEquals(403, $response->getStatusCode());
    }


    /**
     * @return void
     */
    public function testIfPostCanBeDeleted(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}";

        $this->client->request(Request::METHOD_DELETE, $url);

        $this->client->request(Request::METHOD_GET, $url);
        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }
}
