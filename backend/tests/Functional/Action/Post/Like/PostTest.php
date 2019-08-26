<?php

namespace App\Tests\Functional\Action\Post\Like;

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
    public function testIfPostCanBeLikedByAnotherUser(): void
    {
        $this->becomeUser('moderator', 'moderator');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/likes";

        $this->becomeUser('developer', 'developer');

        $this->client->request(Request::METHOD_POST, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertObjectHasAttribute('developer', $content);
        $this->assertObjectHasAttribute('post', $content);
    }

    /**
     * @return void
     */
    public function testIfPostCanNotBeLikedMultipleTimesByAnotherUser(): void
    {
        $this->becomeUser('moderator', 'moderator');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/likes";

        $this->becomeUser('developer', 'developer');

        $this->client->request(Request::METHOD_POST, $url);
        $likeId1 = json_decode($this->client->getResponse()->getContent())->id;

        $this->client->request(Request::METHOD_POST, $url);
        $likeId2 = json_decode($this->client->getResponse()->getContent())->id;

        $this->client->request(Request::METHOD_POST, $url);
        $likeId3 = json_decode($this->client->getResponse()->getContent())->id;

        $this->assertEquals($likeId1, $likeId2);
        $this->assertEquals($likeId2, $likeId3);
    }

    /**
     * @return void
     */
    public function testIfPostCanBeUnLikedByUser(): void
    {
        $this->becomeUser('moderator', 'moderator');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/likes";
        $this->becomeUser('developer', 'developer');

        $this->client->request(Request::METHOD_POST, $url);

        $url = "/api/posts/{$content->id}/likes/{$this->currentUserId}";
        $this->client->request(Request::METHOD_DELETE, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertObjectHasAttribute('developer', $content);
        $this->assertObjectHasAttribute('post', $content);
    }

    /**
     * @return void
     */
    public function testIfRemovingANonExistingLikeReturns200(): void
    {
        $this->becomeUser('moderator', 'moderator');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $this->becomeUser('developer', 'developer');

        $url = "/api/posts/{$content->id}/likes/{$this->currentUserId}";

        $this->client->request(Request::METHOD_DELETE, $url);
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @return void
     */
    public function testIfAdminCanViewLikesOfPost(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'Hello all! This is my first post :)'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/likes";

        $this->becomeUser('admin', 'admin');

        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }
}
