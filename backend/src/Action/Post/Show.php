<?php

namespace App\Action\Post;

use App\Entity\Post;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class Show
{
    /** @var PostService $postService */
    protected $postService;
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param PostService $postService
     * @param View $view
     * @param Context $context
     */
    public function __construct(PostService $postService, View $view, Context $context)
    {
        $this->postService = $postService;
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'post'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get a post by its ID",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Post::class, groups={"post"})
     *    )
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Post $post
     * @return View
     */
    public function __invoke(Post $post): View
    {
        return $this->view->setData($post);
    }
}
