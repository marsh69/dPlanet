<?php

namespace App\Tests\Functional\Action\Post\Image;

use App\Tests\Functional\AuthorizationTrait;
use App\Tests\Functional\JsonRequestTrait;
use App\Tests\Functional\Testcase\FixtureAwareTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PostTest extends FixtureAwareTestCase
{
    const TEST_IMAGE = '/app/src/tests/Resources/test_image.png';
    const TEST_IMAGE_TMP = '/app/src/tests/Resources/test_image.png.tmp';
    const TEST_IMAGE_2 = '/app/src/tests/Resources/test_image_2.png';
    const TEST_IMAGE_2_TMP = '/app/src/tests/Resources/test_image_2.png.tmp';

    use AuthorizationTrait;
    use JsonRequestTrait;

    /** @var Filesystem $fs */
    protected $fs;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->fs = new Filesystem();
        $this->fs->copy(self::TEST_IMAGE, self::TEST_IMAGE_TMP);
        $this->fs->copy(self::TEST_IMAGE_2, self::TEST_IMAGE_2_TMP);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->fs->remove([self::TEST_IMAGE_2_TMP, self::TEST_IMAGE_TMP]);
    }

    /**
     * @return void
     */
    public function testIfImageGetsUploaded(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'This is my first post with an image!'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/image";

        $fileData = [ 'resource' => new UploadedFile(self::TEST_IMAGE_TMP, 'test_image') ];

        $this->client->request(Request::METHOD_POST, $url, [], $fileData, []);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($content->image->publicPath);
    }

    /**
     * @return void
     */
    public function testIfImageGetsUpdated(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'This is my first post with an image!'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/image";

        $fileData = [ 'resource' => new UploadedFile(self::TEST_IMAGE_TMP, 'test_image') ];

        $this->client->request(Request::METHOD_POST, $url, [], $fileData, []);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $oldPublicPath = $content->image->publicPath;

        $fileData = [ 'resource' => new UploadedFile(self::TEST_IMAGE_2_TMP, 'test_image_2') ];

        $this->client->request(Request::METHOD_POST, $url, [], $fileData, []);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEquals($oldPublicPath, $content->image->publicPath);
    }

    /**
     * @return void
     */
    public function testIfImageGetsDeleted(): void
    {
        $this->becomeUser('developer', 'developer');

        $post = ['body' => 'This is my first post with an image!'];

        $response = $this->jsonRequest(Request::METHOD_POST, '/api/posts', $post);
        $content = json_decode($response->getContent());

        $url = "/api/posts/{$content->id}/image";

        $fileData = [ 'resource' => new UploadedFile(self::TEST_IMAGE_TMP, 'test_image') ];

        $this->client->request(Request::METHOD_POST, $url, [], $fileData, []);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($content->image->publicPath);

        $this->client->request(Request::METHOD_DELETE, $url);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(null, $content->image);
    }
}
