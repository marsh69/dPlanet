<?php

namespace App\Tests\Functional\Action\Post\Comment;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Request;

class PostTest extends FixtureAwareTestCase
{
    use AuthorizationTrait;
    use JsonRequestTrait;

    /**
     * @return void
     */
    public function testIfCommentCanBeCreated(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/comments";

        $comment = ['body' => 'Hey welcome!'];

        $response = $this->jsonRequest(Request::METHOD_POST, $url, $comment);
        $content = json_decode($response->getContent());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Hey welcome!', $content->body);
    }

    /**
     * @return void
     */
    public function testIfCommentIsValidated(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/comments";

        $comment = ['body' => '1'];

        $response = $this->jsonRequest(Request::METHOD_POST, $url, $comment);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfCommentCanBeEdited(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/comments";

        $comment = ['body' => 'Hey welcome!'];

        $response = $this->jsonRequest(Request::METHOD_POST, $url, $comment);
        $content = json_decode($response->getContent());

        $url = "/api/comments/{$content->id}";

        $newComment = ['body' => 'Oops, you\'re not welcome here sorry!'];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newComment);
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Oops, you\'re not welcome here sorry!', $content->body);
    }

    /**
     * @return void
     */
    public function testIfCommentIsValidatedWhenEdited(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/comments";

        $comment = ['body' => 'Hey welcome!'];

        $response = $this->jsonRequest(Request::METHOD_POST, $url, $comment);
        $content = json_decode($response->getContent());

        $url = "/api/comments/{$content->id}";

        $newComment = ['body' => '1'];

        $response = $this->jsonRequest(Request::METHOD_PUT, $url, $newComment);
        $content = json_decode($response->getContent());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Validation Failed', $content->title);
    }

    /**
     * @return void
     */
    public function testIfCommentCanBeDeleted(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/comments";

        $comment = ['body' => 'Hey welcome!'];

        $response = $this->jsonRequest(Request::METHOD_POST, $url, $comment);
        $content = json_decode($response->getContent());

        $url = "/api/comments/{$content->id}";

        $this->client->request(Request::METHOD_DELETE, $url);
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hey welcome!', $content->body);
    }
}
